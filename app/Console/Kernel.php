<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Transaction;
use App\User;
use Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // convert all transactions that have status 3 to status 2. and send an email with an attached pdf
            $transactions = Transaction::where('status',3)->get();
            $transaction_ids = [];
            if (count($transactions) > 0) {
                foreach ($transactions as $transaction) {
                    array_push($transaction_ids,$transaction->id);
                }
            }
            $trans = Transaction::whereIn('id',$transaction_ids);
            if ($trans->update(['status'=>2])) {
                // send email
                $send_to = 'young@jayscleaners.com';
                $from = 'noreply@jayscleaners.com';
                // Email customer
                if (Mail::send('emails.account_status', [
                    'transactions' => $transactions
                ], function($message) use ($send_to)
                {
                    $message->to($send_to);
                    $message->subject('Account Status Update For Month '.date('F Y',strtotime(date('Y-m-d H:i:s').' -1 month')).' - created on: '.date('D m/d/Y g:i a'));
                }));

            }
        })->monthlyOn(1, '01:00');
        // ->dailyAt('09:47')
    }
}
