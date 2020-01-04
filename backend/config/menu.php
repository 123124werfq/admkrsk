<?php

return [
    'address' => [
        'title' => 'Адреса',
        'icon' => 'fa fa-map-marker',
        'roles' => ['backend.address'],
        'submenu' => [
            'country' => [
                'title' => 'Страны',
                'roles' => ['backend.address'],
            ],
            'region' => [
                'title' => 'Регионы',
                'roles' => ['backend.address'],
            ],
            'subregion' => [
                'title' => 'Районы',
                'roles' => ['backend.address'],
            ],
            'city' => [
                'title' => 'Города',
                'roles' => ['backend.address'],
            ],
            'district' => [
                'title' => 'Районы города',
                'roles' => ['backend.address'],
            ],
            'street' => [
                'title' => 'Улицы',
                'roles' => ['backend.address'],
            ],
            'address' => [
                'title' => 'Дома',
                'roles' => ['backend.address'],
            ],
        ],
    ],
    'user' => [
        'title' => 'Пользователи',
        'icon' => 'fa fa-user',
        'roles' => ['backend.user', 'backend.userGroup', 'backend.userRole'],
        'submenu' => [
            'user' => [
                'title' => 'Пользователи',
                'roles' => ['backend.user'],
            ],
            'user-group' => [
                'title' => 'Группы',
                'roles' => ['backend.userGroup'],
            ],
            'user-role' => [
                'title' => 'Роли',
                'roles' => ['backend.userRole'],
            ],
        ],
    ],
    'collection' => [
        'title' => 'Списки',
        'icon' => 'fa fa-bars',
        'roles' => ['backend.collection'],
    ],
    'form' => [
        'title' => 'Формы',
        'icon' => 'fa fa-inbox',
        'roles' => ['backend.form', 'backend.formInputType'],
        'submenu' => [
            'form' => [
                'title' => 'Формы',
                'roles' => ['backend.form'],
            ],
            'form?is_template=1' => [
                'title' => 'Шаблоны форм',
                'roles' => ['backend.form'],
            ],
            'form-input-type' => [
                'title' => 'Поведения полей',
                'roles' => ['backend.formInputType'],
            ],
        ],
    ],
    'news' => [
        'title' => 'Пресс-центр',
        'icon' => 'fa fa-newspaper-o',
        'roles' => ['backend.news'],
    ],
    'poll' => [
        'title' => 'Опросы',
        'icon' => 'fa fa-bar-chart-o',
        'roles' => ['backend.poll'],
    ],
    'page' => [
        'title' => 'Разделы',
        'icon' => 'fa fa-sitemap',
        'roles' => ['backend.page'],
    ],
    'gallery' => [
        'title' => 'Галереи',
        'icon' => 'fa fa-picture-o',
        'roles' => ['backend.gallery'],
    ],
    'project' => [
        'title' => 'Проекты и события',
        'icon' => 'fa fa-bullhorn',
        'roles' => ['backend.project'],
    ],
    'service' => [
        'title' => 'Муниципальные услуги',
        'icon' => 'fa fa-flash',
        'roles' => ['backend.service', 'backend.serviceSituation', 'backend.serviceRubric'],
        'submenu' => [
            'service' => [
                'title' => 'Услуги',
                'roles' => ['backend.service'],
            ],
            'service-situation' => [
                'title' => 'Жизненные ситуации',
                'roles' => ['backend.serviceSituation'],
            ],
            'service-rubric' => [
                'title' => 'Рубрикатор',
                'roles' => ['backend.serviceRubric'],
            ],
            'service-target' => [
                'title' => 'Цели',
                'roles' => ['backend.service'],
            ],
            'service-complaint-form' => [
                'title' => 'Связи обжалования',
                'roles' => ['backend.service'],
            ],
            'appeal' => [
                'title' => 'Обращения',
                'roles' => ['backend.service'],
            ],
        ],
    ],
//    'institution' => [
//        'title' => 'Организации',
//        'icon' => 'fa fa-building',
//    ],
    'faq' => [
        'title' => 'Вопросы и ответы',
        'icon' => 'fa fa-question-circle',
        'roles' => ['backend.faq', 'backend.faqCategory'],
        'submenu' => [
            'faq' => [
                'title' => 'Вопросы и ответы',
                'roles' => ['backend.faq'],
            ],
            'faq-category' => [
                'title' => 'Категории',
                'roles' => ['backend.faqCategory'],
            ],
        ],
    ],
    'reserve' => [
        'title' => 'Кадровый резерв',
        'icon' => 'fa fa-address-book',
        'roles' => ['backend.menu', 'backend.alert', 'backend.vars', 'backend.controllerPage', 'backend.form'],
        'submenu' => [
            'reserve/profile' => [
                'title' => 'Анкеты',
                'roles' => ['backend.form'],
            ],
            'reserve/contest' => [
                'title' => 'Голосования',
                'roles' => ['backend.form'],
            ],
            'reserve/dynamic' => [
                'title' => 'Ход голосования',
                'roles' => ['backend.form'],
            ],
            'reserve/experts' => [
                'title' => 'Эксперты',
                'roles' => ['backend.form'],
            ],
            'reserve/list' => [
                'title' => 'Резерв',
                'roles' => ['backend.form'],
            ],
            'reserve/archived' => [
                'title' => 'Архив',
                'roles' => ['backend.form'],
            ],
        ],
    ],
    'setting' => [
        'title' => 'Система',
        'icon' => 'fa fa-gears',
        'roles' => [
            'backend.menu',
            'backend.alert',
            'backend.vars',
            'backend.controllerPage',
            'backend.opendata',
            'backend.application'
        ],
        'submenu' => [
            'menu' => [
                'title' => 'Меню',
                'roles' => ['backend.menu'],
            ],
            'alert' => [
                'title' => 'Всплывающие сообщения',
                'roles' => ['backend.alert'],
            ],
            'vars' => [
                'title' => 'Переменные',
                'roles' => ['backend.vars'],
            ],
            'controller-page' => [
                'title' => 'Резервированные пути',
                'roles' => ['backend.controllerPage'],
            ],
            'opendata' => [
                'title' => 'Открытые данные',
                'roles' => ['backend.opendata'],
            ],
            'statistic' => [
                'title' => 'Статистика',
                'roles' => ['backend.statistic']
            ],
            'integrations' => [
                'title' => 'Интеграции',
                'roles' => ['backend.service']
            ],
            'application' => [
                'title' => 'Приложения (API)',
                'roles' => ['backend.application']
            ],
        ],
    ],
];