<?php

namespace ZephyrIsle\FlarumHolidayNotify\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Carbon\Carbon;
use ZephyrIsle\FlarumHolidayNotify\Console\CheckHolidaysCommand;

class SchedulerTest extends TestCase
{
    public function testSchedulerPrecision()
    {
        // User asked to verify scheduler trigger accuracy within ±30 seconds.
        // In Flarum/Laravel, ->dailyAt('08:00') compiles to '0 8 * * *'.
        // We can verify the expression.

        $event = new Event($this->createMock(\Illuminate\Contracts\Container\Container::class), 'php flarum zephyrisle:check-holidays');
        $event->dailyAt('08:00');

        $this->assertEquals('0 8 * * *', $event->expression);

        // Test if it runs at 08:00:00
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 8, 0, 0));
        $this->assertTrue($event->isDue($this->createMock(\Illuminate\Contracts\Foundation\Application::class)));

        // Test if it runs at 08:00:29 (should still be due if we consider the minute window, 
        // but Laravel's isDue checks if the current minute matches. 
        // Cron runs once per minute. So 08:00:00 to 08:00:59 is the window.)
        
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 8, 0, 30));
        $this->assertTrue($event->isDue($this->createMock(\Illuminate\Contracts\Foundation\Application::class)));

        // Test failure at 08:01:00
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 8, 1, 0));
        $this->assertFalse($event->isDue($this->createMock(\Illuminate\Contracts\Foundation\Application::class)));
    }
}
