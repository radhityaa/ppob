<?php

namespace App\Helpers;

use App\Models\MessageTemplate;
use App\Models\SettingProvider;
use App\Models\Settings;
use App\Models\User;
use App\Models\WhatsappGateway;
use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function getStatus()
    {
        $status = Settings::where('slug', 'settings-notification')->first();

        if ($status->val1 === 'true') {
            return (bool) true;
        } else {
            return (bool) false;
        }
    }

    public static function sendMessage(String $templateName, array $data, $target)
    {
        $sender = WhatsappGateway::first();
        $template = MessageTemplate::where('type', $templateName)->first();
        $target = MyHelper::formatPhoneNumber($target);

        if (!$template) {
            throw new \Exception("Template not found.");
        }

        $message = TemplateHelper::render($template->message, $data);

        return Http::withHeaders([
            'accept' => 'application/json'
        ])->post(env('WA_URL') . '/api/v1/message/single', [
            'sender' => $sender->phone,
            'receiver' => $target,
            'text' => $message
        ]);
    }
}
