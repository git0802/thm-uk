<?php

namespace App\Helpers;

trait VideoHelper
{
    public function parseUrl($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match;
    }

    public function isValidYoutubeHost($url): bool
    {
        return in_array(parse_url($url, PHP_URL_HOST), ['youtube.com', 'www.youtube.com']);
    }
}
