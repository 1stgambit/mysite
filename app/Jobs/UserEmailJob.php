<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\AuthMail;
use Mail;

class UserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

private $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        return $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //dd($this->user->email);
        //Mail::to('1stgambit@rambler.ru')->send(new AuthMail($this->user));
        Mail::to($this->user->email)->send(new AuthMail($this->user));
        //dd('zaebca');
    }
}
