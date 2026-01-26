<?php

namespace App\Http\Helpers;

use App\Models\Admin\MailTemplate;
use App\Models\Setting;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailConfig
{
    //membership buy template
    public function mailFromAdmin($data)
    {
        $temp = MailTemplate::where('type', 'membership_buy')->first();

        $info = Setting::select('smtp_status', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'encryption', 'sender_mail', 'sender_name')
            ->first();

        if ($info->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $info->smtp_host,
                    'port' => $info->smtp_port,
                    'encryption' => $info->encryption,
                    'username' => $info->smtp_username,
                    'password' => $info->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        }
        if ($info->smtp_status == 1) {
            try {
                Mail::send([], [], function (Message $message) use ($data, $temp, $info) {
                    $fromMail = $info->sender_mail;
                    $fromName = $info->sender_name;

                    $message->to($data['toMail'])
                        ->subject($temp->subject)
                        ->from($fromMail, $fromName)
                        ->html($temp->body, 'text/html');

                    if (array_key_exists('invoice', $data)) {
                        $message->attach($data['invoice'], [
                            'as' => 'Invoice',
                            'mime' => 'application/pdf',
                        ]);
                    }
                });
                if (array_key_exists('sessionMessage', $data)) {
                    session()->flash('success', $data['sessionMessage']);
                }
            } catch (\Exception $e) {
                session()->flash('warning', 'Mail could not be sent. Mailer Error: ' . $e);
            }
        }
        return;
    }

    //final send mail
    public static function send($data)
    {
        $info = Setting::select('smtp_status', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'encryption', 'sender_mail', 'sender_name')
            ->first();

        if ($info->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $info->smtp_host,
                    'port' => $info->smtp_port,
                    'encryption' => $info->encryption,
                    'username' => $info->smtp_username,
                    'password' => $info->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                session()->flash('error', $e->getMessage());
                return back();
            }
        }

        if ($info->smtp_status == 1) {
            try {
                Mail::send([], [], function (Message $message) use ($data, $info) {
                    $fromMail = $info->sender_mail;
                    $fromName = $info->sender_name;

                    $message->to($data['recipient'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->html($data['body'], 'text/html');

                       \Log::info('Mail message prepared', [
        'to' => $data['recipient'],
        'subject' => $data['subject'],
        'from' => $fromMail
    ]);

                    if (array_key_exists('invoice', $data)) {
                        $message->attach($data['invoice'], [
                            'as' => 'Invoice',
                            'mime' => 'application/pdf',
                        ]);
                    }
                });
                if (array_key_exists('sessionMessage', $data)) {
                    session()->flash('success', $data['sessionMessage']);
                }
            } catch (\Exception $e) {
                session()->flash('warning', 'Mail could not be sent. Mailer Error: ' . $e);
            }
        }
        return;
    }
}
