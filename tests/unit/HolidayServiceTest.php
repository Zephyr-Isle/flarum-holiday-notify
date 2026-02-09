<?php

namespace ZephyrIsle\FlarumHolidayNotify\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ZephyrIsle\FlarumHolidayNotify\Service\HolidayService;
use Carbon\Carbon;

class HolidayServiceTest extends TestCase
{
    public function testIsGrayModeDate()
    {
        $service = new HolidayService();

        // 12/13
        $date = Carbon::create(2024, 12, 13);
        $this->assertTrue($service->isGrayModeDate($date));

        // 9/18
        $date = Carbon::create(2024, 9, 18);
        $this->assertTrue($service->isGrayModeDate($date));

        // Normal date
        $date = Carbon::create(2024, 1, 1);
        $this->assertFalse($service->isGrayModeDate($date));
    }
}
