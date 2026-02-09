<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;
use ZephyrIsle\FlarumHolidayNotify\Api\Serializer\HolidayConfigSerializer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateHolidayController extends AbstractCreateController
{
    public $serializer = HolidayConfigSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $actor->assertAdmin();

        $data = Arr::get($request->getParsedBody(), 'data', []);
        $attributes = Arr::get($data, 'attributes', []);

        $holiday = new HolidayConfig();
        $holiday->name = Arr::get($attributes, 'name');
        $holiday->identifier = Arr::get($attributes, 'identifier', Str::slug($holiday->name));
        $holiday->type = Arr::get($attributes, 'type', 'gregorian');
        $holiday->month = Arr::get($attributes, 'month');
        $holiday->day = Arr::get($attributes, 'day');
        $holiday->is_enabled = Arr::get($attributes, 'isEnabled', true);
        $holiday->template = Arr::get($attributes, 'template', '');

        $holiday->save();

        return $holiday;
    }
}
