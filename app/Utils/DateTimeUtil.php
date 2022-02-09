<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Utils;


use Carbon\Carbon;
use DateTime;

class DateTimeUtil
{

    public static function currentMicroSecond()
    {
        return round(microtime(true));
    }

    /*from micro second to string format*/
    public static function fromMicroSecond($microSeconds)
    {
        return empty($microSeconds) ? null : date("Y-m-d\TH:i:s.u", $microSeconds);
    }

    /*from string format to micro second*/
    public static function toMicroSecond($dateTimeString)
    {
        return empty($dateTimeString) ? null : strtotime($dateTimeString);
    }
}