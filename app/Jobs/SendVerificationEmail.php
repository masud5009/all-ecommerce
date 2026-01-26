<?php

namespace App\Jobs;

use App\Models\Admin\MailTemplate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 120, 300];

    public function __construct(
        public User $user,
        public string $verificationLink
    ) {}

    public function handle(): void
    {
        try {
            //SMTP Settings
            $settings = Setting::select([
                'smtp_status',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'encryption',
                'sender_mail',
                'sender_name'
            ])->first();

            if ($settings?->smtp_status == 1) {
                Config::set('mail.mailers.smtp', [
                    'transport'  => 'smtp',
                    'host'       => $settings->smtp_host,
                    'port'       => $settings->smtp_port,
                    'encryption' => $settings->encryption ?? 'tls',
                    'username'   => $settings->smtp_username,
                    'password'   => $settings->smtp_password,
                    'timeout'    => null,
                    'auth_mode'  => null,
                ]);
                Config::set('mail.from.address', $settings->sender_mail);
                Config::set('mail.from.name', $settings->sender_name);
            }

            // Email template
            $template = MailTemplate::where('type', 'verify_email')->firstOrFail();

            // Replace template placeholders
            $mailBody = $template->body;
            $mailBody = str_replace('{customer_name}', $this->user->name, $mailBody);
            $mailBody = str_replace('{verification_link}', $this->verificationLink, $mailBody);
            $mailBody = str_replace('{website_title}', app('websiteSettings')->website_title ?? config('app.name'), $mailBody);

            //Send email
            Mail::send([], [], function (Message $message) use ($mailBody, $template, $settings) {
                $message->to($this->user->email)
                    ->subject($template->subject)
                    ->from($settings->sender_mail, $settings->sender_name)
                    ->html($mailBody);
            });
        } catch (\Throwable $e) {
            $this->fail($e);
        }
    }
}
