<?php

namespace App\Content;

class Partners {
    public static function getData() {
        return [
            [
                'image' => asset('/api/public/uploaded/partners/1.png'),
                'link' => '/'
            ],
            [
                'image' => asset('/api/public/uploaded/partners/2.png'),
                'link' => '/'
            ],
            [
                'image' => asset('/api/public/uploaded/partners/3.png'),
                'link' => '/'
            ],
            [
                'image' => asset('/api/public/uploaded/partners/4.png'),
                'link' => '/'
            ],
            [
                'image' => asset('/api/public/uploaded/partners/5.png'),
                'link' => '/'
            ]
        ];
    }
}
