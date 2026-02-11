<?php

namespace ZephyrIsle\FlarumHolidayNotify\Api\Serializer;

use Carbon\Carbon;
use Flarum\Api\Serializer\AbstractSerializer;
use DateTimeInterface;

class HolidayConfigSerializer extends AbstractSerializer
{
    protected $type = 'holiday-configs';

    private function normalizeDate($value): ?DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            try {
                return Carbon::parse($value);
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

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
            'createdAt' => $this->formatDate($this->normalizeDate($model->created_at)),
            'updatedAt' => $this->formatDate($this->normalizeDate($model->updated_at)),
        ];
    }
}
