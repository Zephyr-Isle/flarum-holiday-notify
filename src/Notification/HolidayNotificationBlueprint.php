<?php

namespace ZephyrIsle\FlarumHolidayNotify\Notification;

use Flarum\Notification\Blueprint\BlueprintInterface;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;

class HolidayNotificationBlueprint implements BlueprintInterface
{
    public $message;
    public $holiday;

    public function __construct(HolidayConfig $holiday, $message)
    {
        $this->holiday = $holiday;
        $this->message = $message;
    }

    public function getSubject()
    {
        return $this->holiday;
    }

    public function getFromUser()
    {
        return null;
    }

    public function getData()
    {
        return [
            'message' => $this->message,
            'holidayName' => $this->holiday->name
        ];
    }

    public static function getType()
    {
        return 'holiday';
    }

    public static function getSubjectModel()
    {
        return HolidayConfig::class;
    }
}
