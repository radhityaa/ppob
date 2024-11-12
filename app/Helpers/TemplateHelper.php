<?php

namespace App\Helpers;

class TemplateHelper
{
    public static function render($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
}
