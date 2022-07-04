<?php

namespace App\Listeners;

use App\Events\RegisteredAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RegisteredAccount  $event
     * @return void
     */
    public function handle(RegisteredAccount $event)
    {
        //
    }
}
