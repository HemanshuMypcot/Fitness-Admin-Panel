<?php
/*
    *   Developed by : Nikunj - Mypcot Infotech
    *   Created On : 27-09-2023
    *   https://www.mypcot.com/
*/
namespace App\Utils;

class ApiUtils
{
    public function getViewCountAttribute($value)
    {
        if ($value >= 10000000) {
            return round($value / 10000000, 1).'M';
        }
        elseif ($value >= 100000) {
            return round($value / 100000, 1).'L';
        }
        elseif ($value >= 1000) {
            return round($value / 1000, 1).'K';
        } else {
            return $value;
        }
    }

    public function covertDurationTime($durationInMinutes)
    {
        if ($durationInMinutes >= 60) {
            $durationInHours = floor($durationInMinutes / 60);
            $remainingMinutes = $durationInMinutes % 60;
            $formattedDuration = $durationInHours == 1 ?$durationInHours. ' hour' : $durationInHours.' hours';

            if ($remainingMinutes > 0) {
                $formattedDuration .= ' ' . $remainingMinutes . ' mins session';
            } else {
                $formattedDuration .= ' session';
            }
        } else {
            $formattedDuration = $durationInMinutes . ' mins session';
        }
        return $formattedDuration;
    }
}
