<?php

namespace ZephyrIsle\FlarumHolidayNotify;

use Flarum\Database\AbstractModel;

class HolidayConfig extends AbstractModel
{
    protected $table = 'holiday_configs';
    
    protected $fillable = [
        'name',
        'identifier',
        'type',
        'month',
        'day',
        'duration',
        'is_enabled',
        'template'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'month' => 'integer',
        'day' => 'integer',
        'duration' => 'integer'
    ];
}
