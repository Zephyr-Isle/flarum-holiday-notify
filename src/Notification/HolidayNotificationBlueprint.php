<?php

namespace ZephyrIsle\FlarumHolidayNotify\Notification;

use Flarum\Notification\Blueprint\BlueprintInterface;

class HolidayNotificationBlueprint implements BlueprintInterface
{
    public $message;
    public $holidayName;

    public function __construct($holidayName, $message)
    {
        $this->holidayName = $holidayName;
        $this->message = $message;
    }

    public function getSubject()
    {
        return null;
    }

    public function getSender()
    {
        return null; // System notification
    }

    public function getData()
    {
        return [
            'message' => $this->message,
            'holidayName' => $this->holidayName
        ];
    }

    public static function getType()
    {
        return 'holiday';
    }

    public static function getSubjectModel()
    {
        return null;
    }
}
