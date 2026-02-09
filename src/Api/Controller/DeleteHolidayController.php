<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;

class DeleteHolidayController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $actor->assertAdmin();

        $id = \Illuminate\Support\Arr::get($request->getQueryParams(), 'id');
        $holiday = HolidayConfig::findOrFail($id);

        $holiday->delete();
    }
}
