<?php


namespace App\Helpers;


use Illuminate\Support\Str;

class ProductHelper
{
    public static function fullLink($link)
    {
        if (is_null($link) || Str::startsWith($link, 'http')) {
            return $link;
        }

        return 'https://'.$link;
    }
}