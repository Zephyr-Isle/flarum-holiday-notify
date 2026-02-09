<?php

namespace ZephyrIsle\FlarumHolidayNotify\Console;

use Illuminate\Console\Command;
use ZephyrIsle\FlarumHolidayNotify\Service\HolidayService;
use ZephyrIsle\FlarumHolidayNotify\Job\SendHolidayNotificationsJob;
use Illuminate\Contracts\Queue\Queue;
use Carbon\Carbon;

class CheckHolidaysCommand extends Command
{
    protected $signature = 'zephyrisle:check-holidays';
    protected $description = 'Check for today\'s holidays and schedule notifications';

    protected $holidayService;
    protected $queue;

    public function __construct(HolidayService $holidayService, Queue $queue)
    {
        parent::__construct();
        $this->holidayService = $holidayService;
        $this->queue = $queue;
    }

    public function handle()
    {
        $today = Carbon::now();
        $holidays = $this->holidayService->getHolidaysForDate($today);

        foreach ($holidays as $holiday) {
            $this->info("Found holiday: {$holiday->name}");
            
            // Check if we already sent it? 
            // Implementation detail: we might need a log table 'holiday_logs' to prevent double sending if command runs multiple times.
            // For now, assume cron runs once daily or we use cache to lock.
            
            // Dispatch job
            $this->queue->push(new SendHolidayNotificationsJob($holiday->id));
            $this->info("Scheduled notification for {$holiday->name}");
        }

        if (empty($holidays)) {
            $this->info("No holidays today.");
        }
    }
}
