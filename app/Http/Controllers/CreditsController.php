<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request as Request;
use App;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\FilesController;
class CreditsController extends Controller {

    private $OK = 200;
    private $FORBIDDEN = 403;
    private $NOT_FOUND = 404;
    private $SERVICE_NOT_AVAILABLE = 503;

    private $INITIAL_MOVE = "INICIAL";
    public function addCreditType(Request $request){

        $validator = Validator::make($request->all(), App\creditavailable::$rules);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all(),'request'=>$request]);
        }
        $credit = App\creditavailable::create($request->all());
        $credit->save();
        return response()->json(['error'=>false,'message'=>'Credito Creado.','credit'=>$credit->id],$this->OK);
    }

    public function allCreditTypes(){
        $credit = App\creditavailable::get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }
    public function allCreditApproved(){
        $credit = App\approvedcredit::select('credits_approved.*','applications.projectname','clients.businessname','clients.name','clients.lastname')->join('applications','credits_approved.application','=','applications.id')->leftjoin('clients','applications.idclient','=','clients.id')->where('credits_approved.extends',null)->get();
        if(!$credit->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','credits'=>$credit]);
        }
        return response()->json(['error'=>true,'message'=>'no hay creditos registradas.','credits'=>null]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCreditApproved(Request $request, $id){
        $credit = App\approvedcredit::where('id',$id)->orWhere('extends', $id)->orderBy('start_date', 'asc')->get();
        if($credit->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No hay creditos registrados']);
        }
        $application = App\Application::where('id',$credit->toArray()[0]['application'])->first();
        $client = App\Client::where('id',$application->idclient)->first(['businessname','name','lastname']);
        $lastMove = App\controlcredit::select('controlcredits.*')->join('credits_approved','credits_approved.application','=',DB::raw("'".$application->id."'"))->whereRaw('controlcredits.credit=credits_approved.id')->orderBy('controlcredits.id', 'DESC')->first();
        $lastCondition = App\approvedcredit::where('application',$application->id)->orderBy('id','desc')->first();
        if($credit[0]->type!=3){
            $lastMove = $this->calculatePayByEndOfMonth($lastCondition,$lastMove);
        }
        $moves = array();
        foreach ($credit as $data){
            $moves[(string)$data->id]=App\controlcredit::where('credit',$data->id)->get();
        }
        $name = $client->businessname == null ? $client->name." ".$client->lastname : $client->businesssname;
        //Si el credito es de pagos iguales, hay que obtener su pago mensual
        if (count($credit) == 1 && $credit[0]->type == 3) {
            $monthlyPay = App\EqualMonthlyPay::where('creditid',$credit[0]->id)->first();
            return response()->json(['error'=>false,'applicationID'=>$application->id,'message'=>'ok','lastCondition'=>$lastCondition,'credits'=>$credit,'project'=>$application->projectname, 'monthlyPay'=>$monthlyPay->monthly_pay ,'client'=>$name,'moves'=>$moves,'lastMove'=>$lastMove]);
        }
        return response()->json(['error'=>false,'applicationID'=>$application->id,'message'=>'ok','lastCondition'=>$lastCondition,'credits'=>$credit,'project'=>$application->projectname,'client'=>$name,'moves'=>$moves,'lastMove'=>$lastMove]);
    }
    public function showCreditApprovedByApplication(Request $request,$id)
    {
        $credit = App\approvedcredit::where('application', $id)->where('extends', $id)->get();
        if (!$credit->isEmpty()) {
            return response()->json(['error' => false, 'message' => 'ok', 'credits' => $credit]);
        }
        return response()->json(['error' => true, 'message' => 'no hay creditos registradas.', 'credits' => null]);
    }
    public function showCreditApprovedByClient(Request $request,$id){
        $client = App\Client::where('id',$id)->get();
        if(!$client->isEmpty())
        {

            $applications = App\Application::where('idclient',$id)->get();
            if(!$applications->isEmpty())
            {
                $applicationsArray = $applications->pluck('id')->all();
                $credits = App\approvedcredit::whereIn('application',$applicationsArray)->where('extends',null)->get();
                if(!$credits->isEmpty())
                {
                    return response()->json(['error'=>false,'credits'=>$credits]);
                }
                return response()->json(['error'=>false,'message'=>'no se encontraron creditos.','credits'=>[]]);
            }
            return response()->json(['error'=>false,'message'=>'no se encontraron solicitudes.','credits'=>[]]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro cliente.','credits'=>[]]);
    }
    public function addCreditApproved(Request $request){
        $validator = Validator::make($request->all(), App\approvedcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],$this->OK);
        }else{
            $credit = App\approvedcredit::create($request->all());
            $credit->save();
            Log::warning("TERM");
            Log::warning($credit->term);
            $id = $request['application'];
            $application = App\Application::where('id',$id)->find($id);
            $application->status = 'Autorizado';


            //Cuando el credito es de tipo 1 (pago al final), se hace esto
            if($credit->id != null && $credit->extends==null && $credit->type==1){
                $this->calculateFirstPayFinalMove($credit);

            }
            //Cuando el credito es de tipo 2 (revolvente), se hace esto
            if($credit->extends != null && $credit->type == 2){
                $this->calculateNextRevolvingMove($credit,$request);
            }
            //TODO: Implementar el caso cuando se inserta un credito que ya lleva mas de un mes activo
            if($credit->type == 3){
                $this->calculateEqualPays($credit);
            }

            $application->save();
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id,'application'=>$application],$this->OK);
        }
    }
    public function updateCreditFile($idCredit,$idFile){
        $controlFund = App\controlcredit::where('id',$idCredit)->get();
        //$controlFund->fileid = $idFile;
        if(!$controlFund->isEmpty()){
            try {
                $controlFund = App\controlcredit::where('id',$idCredit)->find($idCredit);
                $controlFund->idfile = $idFile;
                $controlFund->save();
                return response()->json(['error'=>false,'message'=>'Movimiento actualizado correctamente']);
            } catch (\Exception $e) {
                return response()->json(['error'=>false,'message'=>'Movimiento no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }
        $controlFund->save();
    }
    public function addCreditPay(Request $request){
        $validator = Validator::make($request->all(), App\controlcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],$this->OK);
        }else{
            $credit = App\controlcredit::create($request->all());
            $credit->save();
            $approvedcredit = App\approvedcredit::where('id',$credit->credit)->first();
            if($credit->capital_balance < 1 && ($approvedcredit->type==1|| $approvedcredit->type == 3)){
                $approvedcredit->status= 'LIQUIDADO';
                $approvedcredit->save();
                Log::info("Se esta liquidando");

            }else{
                Log::info("No se esta liquidando");
            }
            return response()->json(['error'=>false,'message'=>'ok','credit'=>$credit->id],$this->OK);
        }
    }
    public function addEqualPayDeposit(Request $request){
        $validator = Validator::make($request->all(), App\controlcredit::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],$this->OK);
        }else{
            $approvedcredit = App\approvedcredit::where('id',$request->credit)->first();
            // Si el credito no es de tipo 3, no se pueden hacer este tipo de depositos
            if($approvedcredit->type !=3)
                return response()->json(['error'=>true,'message'=>'Credito no corresponde',],$this->OK);
            $totalPay = $request->pay;
            //obtener el pago mensual calculado anteriormente
            $equalMonthlyPay = App\EqualMonthlyPay::where('creditid',$request->credit)->first();
            $monthlyPay = $equalMonthlyPay->monthly_pay;

            $moves = App\controlcredit::where('credit',$request->credit)->orderBy('period', 'desc')->get();
            if(count($moves) > 0){
                $nextMove = null;
                if($moves[0]->typemove == "DISPOSICION"){
                    Log::info("Creando pago vacio");
                    $nextPay = App\controlcredit::create($request->all());

                    $nextPay->interest_balance = 0;
                    $nextPay->iva_balance = 0;
                    $nextPay->pay = 0;
                    $nextPay->typemove = "PAGO";
                    $nextPay->save();
                    $nextMove = $nextPay;
                }else{
                    $nextMove = $moves[0];
                }
                $moneyLeft = true;
                while($moneyLeft) {
                    Log::info($totalPay);
                    $latestMove = $nextMove;
                    Log::info("Seguimos creando nuevos pagos");
                    $updatedPay = 0;
                    $updatedInterest = 0;
                    $updatedIva = 0;
                    if ($totalPay + $latestMove->pay > $monthlyPay) {
                        Log::info("Pago excedente; Acotaremos y meteremos un nuevo pago");
                        $updatedPay = $monthlyPay;
                        $updatedInterest = $latestMove->interest_balance;
                        $updatedIva = $latestMove->iva_balance;
                        //TODO: Calcular estos valores usando la ultima disposicion
                        if($latestMove->typemove == 'PAGO'){
                            $updatedInterest+=$request->interest_balance;
                            $updatedIva+=$request->iva_balance;
                        }
                        $totalPay -= $monthlyPay - $latestMove->pay;
                        // Guarda el nuevo movimiento
                        $nextPay = App\controlcredit::create($request->all());
                        $nextPay->interest_balance = 0;

                        $nextPay->typemove = $nextMove->typemove;
                        $nextPay->iva_balance = 0;
                        $nextPay->pay = 0;
                        $nextPay->period = $nextMove->period;
                        $nextPay->capital_balance = $latestMove->capital_balance - $monthlyPay;
                        if ($nextPay->capital_balance <= 0.1) {
                            $nextPay->capital_balance = 0;
                            $nextPay->pay = $nextPay->capital_balance;
                            $moneyLeft = false;
                            $approvedcredit->status = "LIQUIDADO";
                            $approvedcredit->save();
                        }
                        $nextPay->save();
                        $nextMove = $nextPay;
                        $nextMove->period = Carbon::parse($nextMove->period)->addMonth();
                        $nextMove->typemove = "ABONO";
                        //Agregar un mes de gracia al credito si se abono extra
                        if ($nextMove->typemove == "ABONO")
                            $approvedcredit->grace_days += 1;
                        $approvedcredit->save();
                    } else {
                        Log::info("Pago faltante; Meteremos lo que falta y ahi cortamos");
                        $updatedPay = $latestMove->pay + $totalPay;
                        $updatedInterest = $latestMove->interest_balance;
                        $updatedIva = $latestMove->iva_balance;
                        //TODO: Calcular estos valores usando la ultima disposicion
                        if($latestMove->typemove == 'PAGO'){
                            $updatedInterest+=$request->interest_balance;
                            $updatedIva+=$request->iva_balance;
                        }
                        $moneyLeft = false;
                    }
                    //Actualizando las variables de pago, iva y balance con los valores calculados arriba
                    $latestMove->pay = $updatedPay;
                    $latestMove->interest_balance = $updatedInterest;
                    $latestMove->iva_balance = $updatedIva;
                    $latestMove->save();
                }
                return response()->json(['error'=>false,'message'=>'OK']);

            }else{
                response()->json(['error'=>true,'message'=>'No hay movimientos'],$this->OK);
            }
        }
    }
    public function addAnalysis(Request $request){
        $validator = Validator::make($request->all(), App\CreditAnalysis::$rules['create']);
        if($validator->fails()){
            return response()->json(['error'=>true,'message'=>'Error de Validaciones.','errors'=>$validator->errors()->all()],$this->OK);
        }else {
            $analysis = App\CreditAnalysis::create($request->all());
            $analysis->save();
            return response()->json(['error'=>false,'message'=>'Analisis agregado correctamente','analisisid'=>$analysis->id],$this->OK);
        }
    }
    public function addAnalysisFile(Request $request,$analysisid){
        if(!$request->has('idapplication')){
            return response()->json(['error'=>true,'message'=>'Se necesita un ID de aplicacion']);
        }
        $FC = new FilesController();
        //$filesRequest = new Request();
        //$filesRequest->replace(['idapplication'=>$request->input('applicationid'),]);
        $response = $FC->add($request);
        $data = $response->getData();
        if(!$data->error){
            $AF = collect();
            $AF=['analysisid'=>$analysisid,'fileid'=>$data->file->id];
            $analysisFile = App\AnalysisFiles::create($AF);
            $analysisFile->save();
        }
        return $response;
    }
    public function removeAnalysisFile($analysisid){

        $analysisFile = App\AnalysisFiles::where('analysisid',$analysisid)->first();
        if(!$analysisFile){
            return response()->json(['error'=>true,'message'=>'Analisis no existe'],200);
        }
        $FC = new FilesController();
        $fileResponse = $FC->DeleteFile($analysisFile->fileid);
        $data = $fileResponse->getData();
        if(!$data->error){
            $analysisFile->delete();
        }
        return $fileResponse;
    }
    public function updateObservation(Request $request,$analysisid){
        $validator = Validator::make($request->all(), App\CreditAnalysis::$rules['update']);
        if(!$validator->fails()){
            $analysis = App\CreditAnalysis::where('id',$analysisid)->first();
            if(!$analysis){
                return response()->json(['error'=>true,'message'=>'Analisis no existe']);
            }
            $analysis->observation = $request->input('observation');
            $analysis->start_date = $request->input('start_date');
            $analysis->save();
            return response()->json(['error'=>false,'message'=>'Observacion actualizada']);
        }
        return response()->json(['error'=>true,'message'=>'Observacion no valida']);
    }
    public function getAnalysis($applicationid){
        $creditAnalysis = App\CreditAnalysis::where('applicationid',$applicationid)->get();
        if($creditAnalysis->isEmpty()){
            return response()->json(['error'=>true,'message'=>'No existe analisis']);
        }
        $analysis=collect();
        $index = 0;
        //$analysis->push('error'=>'error');
        foreach($creditAnalysis as $ANL){
            $analysisFiles = App\AnalysisFiles::where('analysisid',$ANL->id)->get();
            $files = collect();
            $FC = new FilesController();
            $currentFileIndex = 1;
            foreach($analysisFiles as $AF){
                $fileResponse = $FC->ReturnFile($AF->fileid,new Request());
                $files[$currentFileIndex]=$fileResponse->getData();
                Log::warning($fileResponse->getData()->filepath);
                $currentFileIndex++;
            }
            $analysis_entry = collect();
            $analysis_entry['files']=$files;
            $analysis_entry['observacion']=$ANL->observation;
            $analysis_entry['analysisid']=$ANL->id;
            $analysis_entry['start_date']=$ANL->start_date;
            $analysis[$ANL->id]= ($analysis_entry);
            $index++;
        }
        return response()->json(['error'=>false,'analisis'=>$analysis]);
    }
    public function deleteLastMove($idCredit){
        $lastMove = App\controlcredit::where('credit',$idCredit)->orderBy('id','desc')->first();
        if($lastMove == null){
            return response()->json(['error'=>true,'message'=>'No existe un ultimo movimiento para este credito.'],$this->NOT_FOUND);
        }
        $credit = App\approvedcredit::where('id',$lastMove->credit)->first();
        if($credit->status == "LIQUIDADO"){
            return response()->json(['error'=>true,'message'=>'El credito especificado ya esta liquidado.'],$this->FORBIDDEN);
        }
        if($lastMove->typemove == "INICIAL"){
            return response()->json(['error'=>true,'message'=>'No se puede eliminar el movimiento inicial'],$this->FORBIDDEN);
        }
        $limitDate = Carbon::parse($lastMove->created_at);
        $today = Carbon::now();
        if($limitDate->diffInHours($today) < 24){
            $wasDeleted = $lastMove->forceDelete();
            if($wasDeleted == 1){
                return response()->json(['error'=>false,'message'=>'Ultimo movimiento eliminado'],$this->OK);
            }else{
                return response()->json(['error'=>true,'message'=>'Servidor no pudo procesar la peticion'],$this->SERVICE_NOT_AVAILABLE);
            }

        }else{
            return response()->json(['error'=>true,'message'=>'Ultimo movimiento fue hace mas de un dia.'],$this->FORBIDDEN);
        }

    }
    public function liquidate($idApp){
        $numCreditos =  App\approvedcredit::where('application', $idApp)->update(['status'=>'LIQUIDADO']);
        if($numCreditos > 0){
            return response()->json(['error'=>false,'message'=>'Credito liquidado','derp'=>$numCreditos],$this->OK);
        }else{
            return response()->json(['error'=>true,'message'=>'Solicitud no esta vinculada a ningun credito'],$this->NOT_FOUND);
        }

    }

    /*****************************************************************************************************************
     *********************************************** HELPER FUNCTIONS ************************************************
     *****************************************************************************************************************/

    private function calculatePayByEndOfMonth($credit,$lastMove){
        $move = $lastMove;

        if ($lastMove!= null) {
            $move->id = $credit->id;
            $move->date = Carbon::now();
            $lastMoveDate = Carbon::parse($lastMove->period);
            $finalDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term));
            $graceDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term))->addDays(
                intval($credit->grace_days));
            $newDate = Carbon::now()->endOfMonth();
            $dateDif = $lastMoveDate->diffInDays($newDate);
            $dateDifGrace = $dateDif;

            $move->credit = $credit->id;
            $move->period = $newDate;
            $move->capital_balance = floatval($lastMove->capital_balance);
            if($dateDif == 0){//Si no ha pasado ningun dia, el balance interes nuevo es igual al anterior
                $move->interest_balance = floatval($lastMove->interest_balance);
            }else{
                $move->interest_balance = floatval($lastMove->interest_balance) +
                    (floatval($move->capital_balance) * $credit->interest)/(36500/ $dateDif);
            }

            // restar intereses moratorios a margen financiero
            $move->iva_balance = ($move->interest_balance * ($credit->iva / 100));
            $move->interest = $move->interest_balance;
            $move->iva = $move->iva_balance;
            $application = App\Application::where('id',$credit->application)->first();


            if ($newDate->timestamp > $graceDate->timestamp) {
                $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($credit->interest_arrear / 100 / 365) * $dateDifGrace) * ($move->capital_balance + $move->interest_balance));
                //restar intereses moratorios a margen financiero
                $move->interest_arrear_iva_balance = $move->interest_arrear_balance * ($credit->iva / 100);
            } else {
                $move->interest_arrear_balance = 0;
                $move->interest_arrear_iva_balance = 0;
            }
            $move->currency = $credit->currency;

        }
        return $move;

    }
    private function calculateNextRevolvingMove($credit,$request){
        $lastMove = App\controlcredit::where('credit',$credit->extends)->orderBy('id', 'DESC')->first();
        if($lastMove){
            $startDate = Carbon::parse($credit->start_date);
            $finalDate = Carbon::parse($credit->start_date);
            $newDate = Carbon::parse($lastMove->period);
            $dateDif = $startDate->diffInDays($newDate);
            $move = new App\controlcredit();
            $move->credit = $credit->extends;
            $move->period = $credit->start_date;
            $move->capital_balance = floatval($lastMove->capital_balance);
            $move->interest_balance = floatval($lastMove->interest_balance) + (((floatval($credit->interest)/100/365)*floatval($move->capital_balance))*$dateDif);
            $move->iva_balance = ($move->interest_balance*($credit->iva/100));
            $move->interest = $move->interest_balance;
            $move->iva = $move->iva_balance;
            if($finalDate->addMonth(intval($credit->term))->timestamp <= $newDate->timestamp){
                $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($credit->interest_arrear/100/365)*$dateDif)*($move->capital_balance+$move->interest_balance));
                $move->interest_arrear_iva_balance = $move->interest_arrear_balance*($credit->iva/100);
            }else{
                $move->interest_arrear_balance = 0;
                $move->interest_arrear_iva_balance = 0;
            }
            $move->capital_balance = floatval($credit->amount) + floatval($lastMove->capital_balance);
            $move->currency = $credit->currency;
            if($request->has("idref")){
                $move->idref = $request->input("idref");
            }
            $move->typemove = $request->typemove;
            $move->saveOrFail();
        }
        else{
            $move = new App\controlcredit();
            $move->credit = $credit->extends;
            $move->period = $credit->start_date;
            $move->capital_balance = $credit->amount;
            $move->interest_balance = 0;
            $move->iva_balance = 0;
            $move->interest_arrear_balance = 0;
            $move->interest_arrear_iva_balance = 0;
            $move->currency = $credit->currency;
            if($request->has("idref")){
                $move->idref = $request->input("idref");
            }

            $move->typemove = $request->typemove;
            $move->saveOrFail();
        }
    }
    private function calculateFirstPayFinalMove($credit){
        $move = new App\controlcredit();
        $move->credit = $credit->id;
        $move->period = $credit->start_date;
        $move->capital_balance = $credit->amount;
        $move->interest_balance = 0;
        $move->iva_balance = 0;
        $move->interest_arrear_balance = 0;
        $move->interest_arrear_iva_balance = 0;
        $move->currency = $credit->currency;
        $move->typemove = "INICIAL";
        $move->save();
    }
    private function calculateEqualPays($credit){

        // Calcular el pago mensual que se aplicara en cada uno de los meses de pago mensual
        $equalPay = $this->calculateEqualPay($credit,$credit->amount,$credit->term);
        //Guardar en la tabla de pagos mensuales el pago mensual vigente

        $this->updateMonthlyPay($credit->id,$equalPay['monthly_pay']);

        $start_date = Carbon::parse($credit->start_date);
        $TA = $credit->interest/100;
        $interest_balance =$credit->amount*$TA/12;
        $iva_balance = $interest_balance*($credit->iva/100);
        //Calcular el primer movimiento
        $this->createNewEqualPayMove($credit,$credit->amount,$interest_balance,$iva_balance,$start_date);
        $today = Carbon::now();
        $limit = $start_date->diffInMonths($today)-1;
        //Si hay almenos algun mes pendiente por calcular, calcularlo
        if($limit > 0 && $credit->term-1 != 0) {
            $start_date->addMonth();
            $this->calculateMissingPays($credit, $credit->amount, $interest_balance, $iva_balance, $credit->term - 1, $limit, $start_date);
        }
    }
    private function calculateMissingPays(App\approvedcredit $credit,$_amount,$_interest_balance,$_iva_balance,$months,$limit,Carbon $lastMoveDate){
        //grace days = grace months
        //Si un credito aun tiene meses de gracia, ignorar el mes actual, no calcular intereses
        if($credit->grace_days > 0){
            $credit->grace_days--;
            $credit->save();
            if($limit > 0 && $months-1 > 0){
                $this->calculateMissingPays($credit,$_amount,$_interest_balance,$_iva_balance,$months-1,$limit-1,$lastMoveDate->addMonth());
            }
            return;
        }

        $amount = $_amount + $_interest_balance; // Se capitalizan los intereses, sumandolos al balance de capital
        $equalPay = $this->calculateEqualPay($credit,$amount,$months);
        //Crear nuevo movimiento
        $this->createNewEqualPaymove($credit,$amount,$equalPay['interest_balance'],$equalPay['iva_balance'],$lastMoveDate);
        if($limit <= 0 || $months-1 <= 0){
            // Guardar en la tabla de pagos mensuales el pago mensual vigente
            $this->updateMonthlyPay($credit->id,$equalPay['monthly_pay']);
        }else{
            // Seguir calculando pagos mensuales
            $this->calculateMissingPays($credit,$amount,$equalPay['interest_balance'],$equalPay['iva_balance'],$months-1,$limit-1,$lastMoveDate->addMonth());
        }

    }
    private function calculateEqualPay($credit,$amount,$months){

        $TA = $credit->interest/100; //Tasa Anual (Dividida sobre 100 para obtener su valor porcentual)
        $IVA = 1+$credit->iva/100; //IVA (1.16)
        $n = $credit->term; //Numero de Meses
        $PV = $amount; //Capital hasta ahora
        $r = ($TA*$IVA)/12; //Tasa de Interes
        $P = ($r*($PV)) /( 1-pow(1+$r,-$months) ); //Pago a hacer
        $interest_balance = ($PV*$TA)/12;
        $iva_balance = $interest_balance*($credit->iva/100);
        return array(
            'monthly_pay'=>$P,
            'pay'=>$P-$interest_balance-$iva_balance,
            'interest_balance' => $interest_balance,
            'iva_balance' => $iva_balance
        );

    }
    private function createNewEqualPayMove($credit,$amount,$interest_balance,$iva_balance,$period){
        $newMove = new  App\controlcredit();
        $newMove->credit = $credit->id;
        $newMove->period = $period;
        $newMove->interest_balance = $interest_balance;
        $newMove->iva_balance = $iva_balance;
        $newMove->interest_arrear_balance = 0;
        $newMove->interest_arrear_iva_balance = 0;
        $newMove->capital_balance = $amount;
        $newMove->currency = $credit->currency;
        $newMove->typemove = "DISPOSICION";
        $newMove->saveOrFail();
    }
    private function updateMonthlyPay($creditId,$pay){
        $monthlyPay = App\EqualMonthlyPay::where('creditid',$creditId)->first();
        if(!$monthlyPay){
            $monthlyPay = new App\EqualMonthlyPay();
            $monthlyPay->creditid = $creditId;
            $monthlyPay->monthly_pay = $pay;
            $monthlyPay->save();
        }else{
            $monthlyPay->monthly_pay = $pay;
            $monthlyPay->save();
        }
    }
}