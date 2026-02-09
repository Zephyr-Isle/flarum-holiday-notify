<?php

namespace ZephyrIsle\FlarumHolidayNotify\Job;

use Flarum\Queue\AbstractJob;
use Flarum\User\User;
use Flarum\Notification\NotificationSyncer;
use ZephyrIsle\FlarumHolidayNotify\Notification\HolidayNotificationBlueprint;
use ZephyrIsle\FlarumHolidayNotify\Service\OpenAIService;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;

class SendHolidayNotificationsJob extends AbstractJob
{
    protected $holidayConfigId;

    public function __construct($holidayConfigId)
    {
        $this->holidayConfigId = $holidayConfigId;
    }

    public function handle(NotificationSyncer $notifications, OpenAIService $openAI)
    {
        $holiday = HolidayConfig::find($this->holidayConfigId);
        if (!$holiday) {
            return;
        }

        // Generate content (cached or fresh)
        // For simplicity, we generate once per holiday run
        $content = $openAI->generateNotificationContent($holiday->name, $holiday->identifier);
        
        // Use custom template if available
        if (!empty($holiday->template)) {
            // Simple replacement
            $content = str_replace('{content}', $content, $holiday->template);
        }

        // Send to ALL users
        // This might be heavy, so ideally we batch it. 
        // For Flarum, NotificationSyncer handles 'sync' which usually sends to specific users.
        // We can use User::chunk to iterate.
        
        $blueprint = new HolidayNotificationBlueprint($holiday->name, $content);

        User::chunk(100, function ($users) use ($notifications, $blueprint) {
            $notifications->sync($blueprint, $users->all());
        });
    }
}
