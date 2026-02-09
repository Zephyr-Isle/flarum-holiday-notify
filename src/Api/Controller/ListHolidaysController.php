<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use ZephyrIsle\FlarumHolidayNotify\HolidayConfig;
use ZephyrIsle\FlarumHolidayNotify\Api\Serializer\HolidayConfigSerializer;

class ListHolidaysController extends AbstractListController
{
    public $serializer = HolidayConfigSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        return HolidayConfig::all();
    }
}
