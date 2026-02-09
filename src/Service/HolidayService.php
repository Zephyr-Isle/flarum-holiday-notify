<?php

namespace ZephyrIsle\FlarumHolidayNotify\Service;

use Overtrue\ChineseCalendar\Calendar;
use Carbon\Carbon;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;

class HolidayService
{
    /**
     * Get holidays for a specific date
     */
    public function getHolidaysForDate(Carbon $date)
    {
        $holidays = [];
        $configs = HolidayConfig::where('is_enabled', true)->get();

        // Solar date
        $solarMonth = $date->month;
        $solarDay = $date->day;

        // Lunar date
        // Note: The library returns array [year, month, day, isLeap, ...] or similar.
        // We use Overtrue\ChineseCalendar\Calendar to convert Solar to Lunar
        try {
            $lunar = Calendar::solar2lunar($date->year, $date->month, $date->day);
            // $lunar is an array. Key 'lunarMonth', 'lunarDay'.
            $lunarMonth = $lunar['lunarMonth'];
            $lunarDay = $lunar['lunarDay'];
        } catch (\Exception $e) {
            // Fallback or log error. For now, assume strict dependency.
            $lunarMonth = 0;
            $lunarDay = 0;
        }

        foreach ($configs as $config) {
            if ($config->type === 'gregorian') {
                if ($config->month == $solarMonth && $config->day == $solarDay) {
                    $holidays[] = $config;
                }
            } elseif ($config->type === 'lunar') {
                if ($config->month == $lunarMonth && $config->day == $lunarDay) {
                    $holidays[] = $config;
                }
            }
        }

        return $holidays;
    }

    /**
     * Check if today is a specific gray-mode date
     */
    public function isGrayModeDate(Carbon $date)
    {
        // 12/13 and 9/18
        $month = $date->month;
        $day = $date->day;

        if (($month == 12 && $day == 13) || ($month == 9 && $day == 18)) {
            return true;
        }
        return false;
    }
}
