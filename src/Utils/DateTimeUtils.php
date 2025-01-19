<?php

namespace App\Utils;

class DateTimeUtils
{
    public static function timestampMsToSec($timestampMs): int
    {
        return (int) ($timestampMs / 1000);
    }
}
