<?php

return [
    /*''=>[
        'title'=>'Адреса',
        'icon'=>'fa fa-map-marker'
    ],*/
    'address'=>[
        'title'=>'Адреса',
        'icon'=>'fa fa-map-marker',
        'roles' => ['backend'],
//        'submenu'=>[
//            'address'=>[
//                'title'=>'Адреса',
//                'roles' => ['backend'],
//            ],
//            'house'=>[
//                'title'=>'Дома',
//                'roles' => ['backend'],
//            ],
//        ],
    ],
    'user'=>[
        'title'=>'Пользователи',
        'icon'=>'fa fa-user',
        'roles' => ['backend.user'],
    ],
    'collection'=>[
        'title'=>'Списки',
        'icon'=>'fa fa-bars',
        'roles' => ['backend.collection'],
    ],
    'form'=>[
        'title'=>'Формы',
        'icon'=>'fa fa-inbox',
        'roles' => ['backend.form'],
        'submenu'=>[
            'form'=>[
                'title'=>'Формы',
                'roles' => ['backend.form'],
            ],
            'form-input-type'=>[
                'title'=>'Типы полей',
                'roles' => ['backend.form'],
            ],
        ],
    ],
    'news'=>[
        'title'=>'Пресс-центр',
        'icon'=>'fa fa-newspaper-o',
        'roles' => ['backend.news'],
    ],
    'poll'=>[
        'title'=>'Опросы',
        'icon'=>'fa fa-bar-chart-o',
        'roles' => ['backend.poll'],
    ],
    'page'=>[
        'title'=>'Разделы',
        'icon'=>'fa fa-sitemap',
        'roles' => ['backend.page'],
    ],
    'gallery'=>[
        'title'=>'Галереи',
        'icon'=>'fa fa-picture-o',
        'roles' => ['backend.gallery'],
    ],
    'project'=>[
        'title'=>'Проекты и события',
        'roles' => ['backend.menu'],
        'icon'=>'fa fa-bullhorn',
    ],
    'service'=>[
        'title'=>'Муниципальные услуги',
        'icon'=>'fa fa-flash',
        'submenu'=>[
            'service'=>[
                'title'=>'Услуги',
            ],
            'service-situation'=>[
                'title'=>'Жизненные ситуации',
            ],
            'service-rubric'=>[
                'title'=>'Рубрикатор',
            ],
        ],
    ],
//    'institution'=>[
//        'title'=>'Организации',
//        'icon'=>'fa fa-building',
//    ],
    'faq'=>[
        'title'=>'Вопросы и ответы',
        'icon'=>'fa fa-question-circle',
        'roles' => ['backend.faq', 'backend.faq.category', 'backend.faq.section'],
        'submenu'=>[
            'faq'=>[
                'title'=>'Вопросы и ответы',
                'roles' => ['backend.faq'],
            ],
            'faq-category'=>[
                'title'=>'Категории',
                'roles' => ['backend.faq.category'],
            ],
        ],
    ],
    'setting'=>[
        'title'=>'Система',
        'icon'=>'fa fa-gears',
        'roles' => ['backend.menu', 'backend.vars'],
        'submenu'=>[
            'menu'=>[
                'title'=>'Меню',
                'roles' => ['backend.menu'],
            ],
            'alert'=>[
                'title'=>'Всплывающие сообщения',
            ],
            'vars'=>[
                'title'=>'Переменные',
                'roles' => ['backend.vars'],
            ],
            'controller-page'=>[
                'title'=>'Резервированные пути',
            ],
            'opendata'=>[
                'title'=>'Открытые данные',
                'roles' => ['backend.opendata'],
            ],
        ],
    ],
];