<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon as Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        //\App\Console\Commands\Inspire::class,
    ];



    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    private $disposicionMove = 'DISPOSICION';
    private $pagoMove = 'PAGO';
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $credits = App\approvedcredit::where('extends', null)->get();
            $npl_ratio = collect();
            $total_money_loaned= collect();
            $income_net_interest = collect();
            $financial_margin = 0;
            $lastMove = null;
            foreach ($credits as $credit) {
                try{
                    if ($credit->type == 2 || $credit->type == 3) {
                        $lastMove = App\controlcredit::where('credit', $credit->id)->orderBy('id', 'DESC')->firstOrFail();
                    }if($credit->type == 1){
                        $lastCredit = App\approvedcredit::where('extends',$credit->id)->orderBy('id','DESC')->firstOrFail();
                        if($lastCredit){
                            $lastMove = App\controlcredit::where('credit', $lastCredit->id)->orderBy('id', 'DESC')->firstOrFail();
                        }else{
                            $lastMove = App\controlcredit::where('credit', $credit->id)->orderBy('id', 'DESC')->firstOrFail();
                        }
                    }
                }catch (\Exception $ex){
                    $lastMove = null;
                }
                //TODO: Agregar credito de tipo pagos iguales a los datos de las graficas
                if ($lastMove!= null && $credit->type !=3) {
                    $lastMoveDate = Carbon::parse($lastMove->period);
                    $finalDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term));
                    $graceDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term))->addDays(
                        intval($credit->grace_days));
                    $newDate = Carbon::now();
                    $dateDif = $lastMoveDate->diffInDays($newDate);
                    $dateDifGrace = $dateDif;
                    $move = new App\controlcredit();
                    $move->credit = $credit->id;
                    $move->period = $newDate;
                    $move->capital_balance = floatval($lastMove->capital_balance);
                    if($dateDif == 0){//Si no ha pasado ningun dia, el balance interes nuevo es igual al anterior
                        $move->interest_balance = floatval($lastMove->interest_balance);
                    }else{
                        $move->interest_balance = floatval($lastMove->interest_balance) +
                            (floatval($move->capital_balance) * $credit->interest)/(36500/ $dateDif);
                    }
                    //restar intereses moratorios a margen financiero
                    $financial_margin+=ceil($move->interest_balance);
                    $move->iva_balance = ($move->interest_balance * ($credit->iva / 100));
                    $move->interest = $move->interest_balance;
                    $move->iva = $move->iva_balance;
                    $application = App\Application::where('id',$credit->application)->first();
                    if(!isset($total_money_loaned[$application->idclient])){
                        $total_money_loaned[$application->idclient] = $move->capital_balance;
                    }else{
                        $total_money_loaned[$application->idclient] += $move->capital_balance;
                    }
                    //Inicializando variables para indice de morosidad
                    if(!isset($npl_ratio[$application->idclient])){
                        $npl_ratio[$application->idclient] = collect();
                        $npl_ratio[$application->idclient]->expired_money = 0;
                        $npl_ratio[$application->idclient]->grace_money = 0;
                        $npl_ratio[$application->idclient]->active_money = 0;
                    }
                    //Calculando indice de morosidad en el siguiente orden:
                    //--Cantidad activa
                    //--cantidad en dias de gracia
                    //--Cantidad expirada
                    if($newDate->lt($finalDate)){
                        $npl_ratio[$application->idclient]->active_money+=$move->capital_balance;
                    }
                    if($newDate->gt($finalDate)&& $newDate->lt($graceDate)){
                        $npl_ratio[$application->idclient]->grace_money+=$move->capital_balance;
                    }
                    if($newDate->gt($graceDate)){
                        $npl_ratio[$application->idclient]->expired_money+=$move->capital_balance;
                    }
                    //Inicializando intereses por cliente en interes balance, si no lo tiene


                    if ($newDate->timestamp > $graceDate->timestamp) {
                        $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($credit->interest_arrear / 100 / 365) * $dateDifGrace) * ($move->capital_balance + $move->interest_balance));
                        //restar intereses moratorios a margen financiero
                        $financial_margin+=ceil($move->interest_arrear_balance);
                        $move->interest_arrear_iva_balance = $move->interest_arrear_balance * ($credit->iva / 100);
                    } else {
                        $move->interest_arrear_balance = 0;
                        $move->interest_arrear_iva_balance = 0;
                    }
                    if(!isset($income_net_interest[$application->idclient])){
                        $income_net_interest[$application->idclient]=$move->capital_balance+$move->interest_balance
                            +$move->interest_arrear_balance+$move->interest_arrear_iva_balance+$move->iva_balance;
                    }else{//Si lo tiene, lo agregamos a una sumatoria
                        $income_net_interest[$application->idclient]+=$move->capital_balance+$move->interest_balance
                            +$move->interest_arrear_balance+$move->interest_arrear_iva_balance+$move->iva_balance;
                    }
                    $move->currency = $credit->currency;
                    $move->save();
                } else {
                    if($credit->type == 1){
                        $move = new App\controlcredit();
                        $move->credit = $credit->id;
                        $move->period = $credit->start_date;
                        $move->capital_balance = $credit->amount;
                        $move->interest_balance = 0;
                        $move->iva_balance = 0;
                        $move->interest_arrear_balance = 0;
                        $move->interest_arrear_iva_balance = 0;
                        $move->currency = $credit->currency;
                        $move->save();
                    }
                }
                //Caso en el que el tipo sea de tipo pagos iguales. Hay que checar si ya paso un mes desde
                //el ultimo movimiento
                if($credit->type==3){
                    if($lastMove && $lastMove->typemove==$this->disposicionMove){
                        Log::warning("Credito ".$credit->id."Esta atrasado");
                        //Si entramos aqui, significa que el cliente no hizo su pago.
                        $this->calculateMissingPay($credit,$lastMove);
                    }
                }
                unset($lastMove);
                unset($credit);

            }
            //Calculando valores para la tabla "Cartera promedio"
            $this->getAverageMoneyLoaned($total_money_loaned);

            //Calculando valores para la tabla "Ingreso de intereses neto"
            $this->getInterestNetIncome($income_net_interest);

            //Calculando valores para la tabla "Indice de morosidad"
            $this->getNPLRatio($npl_ratio);
            //Fondeadores
            //Fondeadores
            //Fondeadores
            $total_money_borrowed = $this->calculateFundCredits($financial_margin);
            $this->getAverageMoneyBorrowed($total_money_borrowed);



        })->everyMinute();//->monthlyOn(1, '05:00');
    }

    private function calculateMissingPay($credit,$lastMove){
        $amount = $lastMove->capital_balance + $lastMove->interest_balance + $lastMove->_iva_balance;
        $creditEndDate= Carbon::parse($credit->start_date)->addMonths($credit->term);
        $monthsLeft = $creditEndDate->diffInMonths();
        Log::warning("Months left: ".$monthsLeft);
        Log::warning("end date: ");
        Log::warning($creditEndDate);

        if($monthsLeft==0){
            return;
        }
        $TA = $credit->interest/100; //Tasa Anual (Dividida sobre 100 para obtener su valor porcentual)
        $IVA = 1+$credit->iva/100; //IVA (1.16)
        $n = $monthsLeft; //Numero de Meses
        $PV = $amount; //Capital hasta ahora
        $r = ($TA*$IVA)/12; //Tasa de Interes
        $P = ($r*($PV)) /( 1-pow(1+$r,-$n) ); //Pago a hacer
        $interest_balance = ($amount*$TA)/12;
        $interest_iva_balance = $interest_balance*($credit->iva/100);

        Log::warning("Kernel detecto que cliente no pago.");
        Log::warning($monthsLeft);
        Log::warning($PV);
        Log::warning($P);
        Log::warning($r);

        //Crear nuevo movimiento

        $newMove = new  App\controlcredit();
        $newMove->credit = $credit->id;
        $newMove->period = $credit->start_date;
        $newMove->interest_balance =$interest_balance;
        $newMove->iva_balance = $interest_iva_balance;
        $newMove->interest_arrear_balance = 0;
        $newMove->interest_arrear_iva_balance = 0;
        $newMove->capital_balance = $amount;
        $newMove->currency = $credit->currency;
        $newMove->typemove = "DISPOSICION";
        $newMove->saveOrFail();

        //Guardar en la tabla de pagos mensuales el pago mensual vigente
        $monthlyPay = App\EqualMonthlyPay::where('creditid',$credit->id)->first();
        if(!$monthlyPay){
            $monthlyPay = new App\EqualMonthlyPay();
            $monthlyPay->creditid = $credit->id;
            $monthlyPay->monthly_pay = $P;
            $monthlyPay->save();
        }else{
            $monthlyPay->monthly_pay = $P;
            $monthlyPay->save();
        }
    }
    private function calculateFundCredits($financial_margin){

        $funds = App\fund::where('extends', null)->get();
        $total_money_borrowed= collect();
        foreach ($funds as $fund) {
            try{
                $lastMove = App\Control_Fund::where('credit', $fund->id)->orderBy('id', 'DESC')->firstOrFail();

            }catch (\Exception $ex){
                $lastMove = null;
            }
            if ($lastMove!= null) {
                $lastMovePeriod = Carbon::parse($lastMove->period);
                $graceDate = Carbon::parse($lastMove->period)->addMonth(intval($fund->term))->addDays(intval($fund->grace_days));
                $newDate = Carbon::now();
                $dateDif = $lastMovePeriod->diffInDays($newDate);
                $dateDifGrace = $dateDif;//$newDate->diffInDays($graceDate);
                $move = new App\Control_Fund();
                $move->credit = $fund->id;
                $move->period = $newDate;
                Log::warning($dateDif);
                $move->capital_balance = floatval($lastMove->capital_balance);
                if($dateDif == 0){
                    $move->interest_balance = floatval($lastMove->interest_balance);
                }else{
                    $move->interest_balance = floatval($lastMove->interest_balance) +
                        (floatval($move->capital_balance) * $fund->interest)/(36500/ $dateDif);
                }

                //restar intereses moratorios a margen financiero
                $financial_margin-=ceil($move->interest_balance);
                $move->iva_balance = ($move->interest_balance * ($fund->iva / 100));
                $move->interest = $move->interest_balance;
                $move->iva = $move->iva_balance;


                if ($newDate->timestamp > $graceDate->timestamp) {
                    $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($fund->interest_arrear / 100 / 365) * $dateDifGrace) * ($move->capital_balance + $move->interest_balance));
                    //restar intereses moratorios a margen financiero
                    $financial_margin-=ceil($move->interest_arrear_balance);
                    $move->interest_arrear_iva_balance = $move->interest_arrear_balance * ($fund->iva / 100);
                } else {
                    $move->interest_arrear_balance = 0;
                    $move->interest_arrear_iva_balance = 0;
                }
                $move->currency = $fund->currency;
                if(!isset($total_money_borrowed[$fund->idstock])){
                    $total_money_borrowed[$fund->idstock] = $move->capital_balance+$move->interest_balance
                        +$move->interest_arrear_balance+$move->interest_arrear_iva_balance+$move->iva_balance;
                }else{
                    $total_money_borrowed[$fund->idstock] += $move->capital_balance+$move->interest_balance
                        +$move->interest_arrear_balance+$move->interest_arrear_iva_balance+$move->iva_balance;
                }
                $move->save();
            }
            unset($lastMove);
            unset($fund);

        }
        //Post a la tabla de margen financiero
        $newFinancialMargin = new App\Financial_Margin();
        $newFinancialMargin->financial_margin = $financial_margin;
        $newFinancialMargin->save();
        return $total_money_borrowed;
    }
    private function getAverageMoneyLoaned($total_money_loaned){
        try{
            $idsample = App\Average_Money_Loaned::all();
            if($idsample->isEmpty()) $idsample = 1;
            else $idsample = $idsample->max('idsample')+1;
            //Iteramos cada cliente, y su cantidad que tiene prestada, para meterlo a la tabla
            foreach ($total_money_loaned as $client => $money_loaned){
                $newEntry = new App\Average_Money_Loaned();
                $newEntry->idsample = $idsample;
                $newEntry->idclient = $client;
                $newEntry->money_loaned = $money_loaned;
                $newEntry->save();
            }
        }catch (\Exception $ex){
            Log::error("No se pudo calcular la grafica 'Cartera promedio'");
            Log::error($ex);
        }
    }

    private function getNPLRatio($npl_ratio){
        try{
            $idsample = App\NPL_Ratio::all();
            if($idsample->isEmpty()) $idsample = 1;
            else $idsample = $idsample->max('idsample')+1;
            //Iteramos cada cliente, y sus tres cantidades de dinero que deben, para meterlos a la tabla
            foreach ($npl_ratio as $client => $total_money){
                $newEntry = new App\NPL_Ratio();
                $newEntry->idsample = $idsample;
                $newEntry->idclient = $client;
                $newEntry->active_money = $total_money->active_money;
                $newEntry->grace_money = $total_money->grace_money;
                $newEntry->expired_money = $total_money->expired_money;
                $newEntry->save();
            }
        }catch (\Exception $ex){
            Log::error("No se pudo calcular la grafica 'Indice de Morosidad'");
            Log::error($ex);
        }
    }
    private function getInterestNetIncome($interest_income){

        //TODO: Verificar si funcion usa el parametro correcto (Se supone que calcule interes neto, pero itera el dinero
        //TODO: total prestado)
        try{
            //Calculamos el ID de muestra
            $idsample = App\Interest_Net_Income::all();
            if($idsample->isEmpty()) $idsample = 1; //1, si es la primera muestra que se calcula
            else $idsample = $idsample->max('idsample')+1; //ultimaMuestra + 1, si ya hay muestras
            //Iteramos cada cliente, y la sumatoria de sus intereses, para meterlos a la tabla

            foreach ($interest_income as $client => $interest_net_income){
                $newEntry = new App\Interest_Net_Income();
                $newEntry->idsample = $idsample;
                $newEntry->idclient = $client;
                $newEntry->interest_net_income = $interest_net_income;
                $newEntry->save();
            }
        }catch (\Exception $ex){
            Log::error("No se pudo calcular la grafica 'Ingreso de intereses neto'");
            Log::error($ex);
        }
    }
    private function getAverageMoneyBorrowed($total_money_borrowed){
        //Try-catch que calcula el registro de deuda promedio
        try{
            $idsample = App\Average_Money_Borrowed::all();
            if($idsample->isEmpty()) $idsample = 1;
            else $idsample = $idsample->max('idsample')+1;
            foreach ($total_money_borrowed as $stockholder => $money_borrowed){
                $newEntry = new App\Average_Money_Borrowed();
                $newEntry->idsample = $idsample;
                $newEntry->idstockholder = $stockholder;
                $newEntry->money_borrowed = $money_borrowed;
                $newEntry->save();
            }
        }catch (\Exception $ex){
            Log::error("No se pudo calcular la grafica 'Deuda Promedio'");
            Log::error($ex);
        }
    }
}
