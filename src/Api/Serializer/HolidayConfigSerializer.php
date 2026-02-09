<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;

class HolidayConfigSerializer extends AbstractSerializer
{
    protected $type = 'holiday-configs';

    protected function getDefaultAttributes($model)
    {
        return [
            'name' => $model->name,
            'identifier' => $model->identifier,
            'type' => $model->type,
            'month' => $model->month,
            'day' => $model->day,
            'duration' => $model->duration,
            'is_enabled' => $model->is_enabled,
            'template' => $model->template,
            'createdAt' => $this->formatDate($model->created_at),
            'updatedAt' => $this->formatDate($model->updated_at),
        ];
    }
}
