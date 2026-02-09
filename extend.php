<?php

use Flarum\Extend;
use ZephyrIsle\FlarumHolidayNotify\Api\Controller;
use ZephyrIsle\FlarumHolidayNotify\Console\CheckHolidaysCommand;
use ZephyrIsle\FlarumHolidayNotify\Notification\HolidayNotificationBlueprint;
use ZephyrIsle\FlarumHolidayNotify\Service\HolidayService;
use Flarum\Frontend\Document;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less')
        ->content(function (Document $document) {
            $service = resolve(HolidayService::class);
            if ($service->isGrayModeDate(\Carbon\Carbon::now())) {
                $document->head[] = '<script>window.flarumHolidayGrayMode = true;</script>';
                $document->head[] = '<style>
                    .gray-overlay {
                        position: fixed;
                        top: 0; left: 0; width: 100vw; height: 100vh;
                        backdrop-filter: grayscale(100%);
                        -webkit-backdrop-filter: grayscale(100%);
                        z-index: 9000;
                        pointer-events: none;
                    }
                    .Avatar, .UserCard-avatar {
                        position: relative;
                        z-index: 9001;
                    }
                </style>';
            }
        }),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    (new Extend\Locales(__DIR__.'/resources/locale')),

    (new Extend\Routes('api'))
        ->get('/holiday-configs', 'holiday.index', Controller\ListHolidaysController::class)
        ->post('/holiday-configs', 'holiday.create', Controller\CreateHolidayController::class)
        ->patch('/holiday-configs/{id}', 'holiday.update', Controller\UpdateHolidayController::class)
        ->delete('/holiday-configs/{id}', 'holiday.delete', Controller\DeleteHolidayController::class),

    (new Extend\Console())
        ->command(CheckHolidaysCommand::class)
        ->schedule(CheckHolidaysCommand::class, function ($event) {
            $event->dailyAt('08:00');
        }),

    (new Extend\Notification())
        ->type(HolidayNotificationBlueprint::class, ['alert']),
];
