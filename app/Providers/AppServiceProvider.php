<?php

namespace App\Providers;

use App\Models\MailConfig;
use Illuminate\Support\ServiceProvider;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $mail_setting = MailConfig::first();
        if ($mail_setting) {
            $data = [
                'driver' => $mail_setting->mail_transport,
                'host' => $mail_setting->mail_host,
                'port' => $mail_setting->mail_port,
                'encryption' => $mail_setting->mail_encryption,
                'username' => $mail_setting->mail_username,
                'password' => $mail_setting->mail_password,
                'from' => [
                    'address' => $mail_setting->mail_from,
                    'name' => 'Practice Mail Sent',
                ],
            ];
            Config::set('mail', $data);
        }
    }
}
