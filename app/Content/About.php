<?php

namespace App\Content;

class About {
    public static function getData() {
        return [
            [
                'title' => 'Делаем',
                'description' => 'С 2022 года мы устраиваем множество мероприятий, для того, чтобы каждый был вовлечён в ведение здорового образа жизни и занятием спортом',
                'image' => asset('/api/public/images/1.png')
            ],
            [
                'title' => 'Молодежь',
                'description' => 'Основная аудитория наших мероприятий это молодёжь, которая начинает вовлекаться в спортивные мероприятия и начинает вести здоровый образ жизни',
                'image' => asset('/api/public/images/2.jpg')
            ],
            [
                'title' => 'Здоровой',
                'description' => 'Каждый человек должен быть здоровым, и мы придерживаемся этого мнения',
                'image' => asset('/api/public/images/3.png')
            ]
        ];
    }
}
