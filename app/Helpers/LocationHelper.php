<?php

namespace App\Helpers;

/**
 * Location helper.
 *
 * @package App\Helpers
 */
trait LocationHelper
{
    /**
     * Get current application location
     *
     * @return string
     */
    public static function getLocation(): ?string
    {
        return config('app.location');
    }

    /**
     * Get application date format
     *
     * @return string
     */
    public static function getDateFormat(): ?string
    {
        $location = self::getLocation();

        return config('locations.'.$location.'.date_format', 'd/m/y');
    }

    /**
     * Get application date format with full year
     *
     * @return string
     */
    public static function getDateFormatFull(): ?string
    {
        $location = self::getLocation();

        return config('locations.'.$location.'.date_format_full', 'd/m/Y');
    }

    /**
     * Get application currency
     *
     * @return array
     */
    public static function getCurrency(): ?array
    {
        $location = self::getLocation();

        return [
            'currency' => config('locations.'.$location.'.currency', 'GBP'),
            'symbol'   => config('locations.'.$location.'.currency', '£'),
        ];
    }

}
