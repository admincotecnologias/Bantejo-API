<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App;
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
    protected function schedule(Schedule $schedule)
    {
        //
        /*$schedule->call(function () {
            $banco = App\Bank::create(['name' => 'NAN']);
            $banco->save();
        })->everyMinute();*/

        $schedule->call(function () {
            $credits = App\approvedcredit::where('extends', null)->get();
            foreach ($credits as $credit) {
                try{
                    if ($credit->type == 2) {
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
                if ($lastMove!= null) {
                    $startDate = Carbon::parse($credit->start_date);
                    $finalDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term));
                    $graceDate = Carbon::parse($credit->start_date)->addMonth(intval($credit->term))->addDays(intval($credit->grace_days));
                    $newDate = Carbon::now();
                    $dateDif = $startDate->diffInDays($newDate);
                    $dateDifGrace = $newDate->diffInDays($graceDate);
                    $move = new App\controlcredit();
                    $move->credit = $credit->id;
                    $move->period = $newDate;
                    $move->capital_balance = floatval($lastMove->capital_balance);
                    $move->interest_balance = floatval($lastMove->interest_balance) + (((floatval($credit->interest) / 100 / 365) * floatval($move->capital_balance)) * $dateDif);
                    $move->iva_balance = ($move->interest_balance * ($credit->iva / 100));
                    $move->interest = $move->interest_balance;
                    $move->iva = $move->iva_balance;
                    if ($newDate->timestamp > $graceDate->timestamp) {
                        $move->interest_arrear_balance = $lastMove->interest_arrear_balance + ((($credit->interest_arrear / 100 / 365) * $dateDifGrace) * ($move->capital_balance + $move->interest_balance));
                        $move->interest_arrear_iva_balance = $move->interest_arrear_balance * ($credit->iva / 100);
                    } else {
                        $move->interest_arrear_balance = 0;
                        $move->interest_arrear_iva_balance = 0;
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
                unset($lastMove);
                unset($credit);
            }
        })->monthlyOn(1, '05:00');
    }
}
