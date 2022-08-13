<?php

namespace App\Content;

use App\Models\Event;

class Slider {
    public static function getData() {
        return [
            [
                'id' => 1,
                'background' => url('/api/public/images/slider1.jpg'),
                'title' => 'Summer Runner — 2022',
                'description' => 'Команда ChefRun проводит новый летний забег, это не только спортивная активность, это возможность сделать мир чуть лучше.',
                'type' => 'event',
                'event' => Event::query()->where('id', 8)->first()
            ],
            [
                'id' => 2,
                'background' => url('/api/public/images/slider2.jpg'),
                'title' => 'Летняя ярмарка спорта — 2022',
                'description' => 'Поучавствуйте в первой нашей ярмарке посвященной спорту. Начиная с 12 июня',
                'type' => 'event',
                'event' => Event::query()->where('id', 9)->first()
            ]
        ];
    }
}
