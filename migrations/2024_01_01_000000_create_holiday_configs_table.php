<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (!$schema->hasTable('holiday_configs')) {
            $schema->create('holiday_configs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('identifier')->unique(); // e.g. 'new_year', 'spring_festival'
                $table->string('type')->default('gregorian'); // 'gregorian' or 'lunar'
                $table->integer('month');
                $table->integer('day');
                $table->integer('duration')->default(1); // How many days
                $table->boolean('is_enabled')->default(true);
                $table->text('template')->nullable(); // Custom notification template
                $table->timestamps();
            });

            // Seed default holidays
            $connection = $schema->getConnection();
            $now = \Carbon\Carbon::now();
            
            $defaults = [
                ['元旦', 'new_year', 'gregorian', 1, 1],
                ['春节', 'spring_festival', 'lunar', 1, 1],
                ['元宵节', 'lantern_festival', 'lunar', 1, 15],
                ['清明节', 'qingming', 'gregorian', 4, 4], // Note: Qingming varies, but for simplicity/demo we might use 4/4 or handle specially. User said 4.4-4.6, usually 4.4 or 4.5. I'll handle dynamic logic in code, but config needs to exist.
                ['劳动节', 'labor_day', 'gregorian', 5, 1],
                ['端午节', 'dragon_boat', 'lunar', 5, 5],
                ['中秋节', 'mid_autumn', 'lunar', 8, 15],
                ['国庆节', 'national_day', 'gregorian', 10, 1],
                ['重阳节', 'double_ninth', 'lunar', 9, 9],
                ['国家公祭日', 'memorial_day_1213', 'gregorian', 12, 13],
                ['九一八事变', 'memorial_day_0918', 'gregorian', 9, 18],
            ];

            foreach ($defaults as $holiday) {
                $connection->table('holiday_configs')->insert([
                    'name' => $holiday[0],
                    'identifier' => $holiday[1],
                    'type' => $holiday[2],
                    'month' => $holiday[3],
                    'day' => $holiday[4],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('holiday_configs');
    }
];
