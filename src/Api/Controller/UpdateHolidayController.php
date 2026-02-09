<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;
use ZephyrIsle\FlarumHolidayNotify\Api\Serializer\HolidayConfigSerializer;
use Illuminate\Support\Arr;

class UpdateHolidayController extends AbstractShowController
{
    public $serializer = HolidayConfigSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);

        $actor->assertAdmin();

        $holiday = HolidayConfig::findOrFail($id);
        
        $attributes = Arr::get($data, 'attributes', []);

        if (isset($attributes['isEnabled'])) {
            $holiday->is_enabled = $attributes['isEnabled'];
        }
        
        if (isset($attributes['template'])) {
            $holiday->template = $attributes['template'];
        }

        // Add other fields update logic if needed
        if (isset($attributes['month'])) $holiday->month = $attributes['month'];
        if (isset($attributes['day'])) $holiday->day = $attributes['day'];
        if (isset($attributes['type'])) $holiday->type = $attributes['type'];

        $holiday->save();

        return $holiday;
    }
}
