<?php

use App\Models\Navigation;
use App\Models\RechargeTitle;
use App\Models\Role;

if (!function_exists('getMenus')) {
    function getMenus()
    {
        return Navigation::with('subMenus')->orderBy('sort', 'desc')->get();
    }
}

if (!function_exists('getRechargeTitles')) {
    function getRechargeTitles()
    {
        return RechargeTitle::get();
    }
}

if (!function_exists('getRoles')) {
    function getRoles()
    {
        return Role::where('name', '!=', 'admin')->get();
    }

    function getAllRoles()
    {
        return Role::get();
    }
}

if (!function_exists('getBreadcrumbs')) {
    function getBreadcrumbs($route, $params = [])
    {
        $segments = [];

        // Tambahkan segmen "Home"
        $segments[] = ['url' => route('home'), 'text' => 'Home'];

        // Tambahkan segmen tambahan berdasarkan route dan parameter yang diberikan
        switch ($route) {
            case 'library':
                $segments[] = ['url' => route('library'), 'text' => 'Library'];
                break;
            case 'permissions.index':
                $segments[] = ['url' => route('permissions.index'), 'text' => 'Permissions'];
                break;
                // Tambahkan case tambahan untuk setiap route yang Anda perlukan
            default:
                // Jika tidak ada route yang cocok, tidak menambahkan segmen tambahan
                break;
        }

        // Tambahkan segmen terakhir (segmen aktif)
        $segments[] = ['text' => 'Create'];

        return $segments;
    }
}

if (!function_exists('randomLetters')) {
    function randomLetters($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 1, $length);
    }
}

if (!function_exists('formatRupiahToNumber')) {
    function formatRupiahToNumber($value)
    {
        $number = preg_replace('/[^\d]/', '', $value);
        return $number;
    }
}
