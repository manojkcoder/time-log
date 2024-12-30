<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class UpdateLastLogin
{
    public function __construct(){
        
    }
    public function handle(Login $event): void{
        $user = $event->user;
        $user->update(['last_login' => now()]);
    }
}