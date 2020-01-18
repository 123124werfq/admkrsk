<?php

use common\models\Address;
use common\models\Alert;
use common\models\Application;
use common\models\Collection;
use common\models\ControllerPage;
use common\models\Faq;
use common\models\FaqCategory;
use common\models\Form;
use common\models\FormInputType;
use common\models\Gallery;
use common\models\Menu;
use common\models\News;
use common\models\Opendata;
use common\models\Page;
use common\models\Poll;
use common\models\Project;
use common\models\Service;
use common\models\ServiceRubric;
use common\models\ServiceSituation;
use common\models\Statistic;
use common\models\User;
use common\models\UserGroup;
use common\models\UserRole;
use common\models\Vars;

return [
    'address' => [
        'title' => 'Адреса',
        'icon' => 'fa fa-map-marker',
        'roles' => [
            'menu.address' => ['class' => Address::class],
        ],
        'submenu' => [
            'country' => [
                'title' => 'Страны',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'region' => [
                'title' => 'Регионы',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'subregion' => [
                'title' => 'Районы',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'city' => [
                'title' => 'Города',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'district' => [
                'title' => 'Районы города',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'street' => [
                'title' => 'Улицы',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'address' => [
                'title' => 'Дома',
                'roles' => [
                    'menu.address' => ['class' => Address::class],
                ],
            ],
            'fias-update-history' => [
                'title' => 'История обновлений',
                'roles' => ['backend.address'],
            ],
        ],
    ],
    'user' => [
        'title' => 'Пользователи',
        'icon' => 'fa fa-user',
        'roles' => [
            'menu.user' => ['class' => User::class],
            'menu.userGroup' => ['class' => UserGroup::class],
            'menu.userRole' => ['class' => UserRole::class],
        ],
        'submenu' => [
            'user' => [
                'title' => 'Пользователи',
                'roles' => [
                    'menu.user' => ['class' => User::class],
                ],
            ],
            'user-group' => [
                'title' => 'Группы',
                'roles' => [
                    'menu.userGroup' => ['class' => UserGroup::class],
                ],
            ],
            'user-role' => [
                'title' => 'Роли',
                'roles' => [
                    'menu.userRole' => ['class' => UserRole::class],
                ],
            ],
        ],
    ],
    'collection' => [
        'title' => 'Списки',
        'icon' => 'fa fa-bars',
        'roles' => [
            'menu.collection' => ['class' => Collection::class],
        ],
    ],
    'form' => [
        'title' => 'Формы',
        'icon' => 'fa fa-inbox',
        'roles' => [
            'menu.form' => ['class' => Form::class],
            'menu.formInputType' => ['class' => FormInputType::class],
        ],
        'submenu' => [
            'form' => [
                'title' => 'Формы',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'form?is_template=1' => [
                'title' => 'Шаблоны форм',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'form-input-type' => [
                'title' => 'Поведения полей',
                'roles' => [
                    'menu.formInputType' => ['class' => FormInputType::class],
                ],
            ],
        ],
    ],
    'news' => [
        'title' => 'Пресс-центр',
        'icon' => 'fa fa-newspaper-o',
        'roles' => [
            'menu.news' => ['class' => News::class],
        ],
    ],
    'poll' => [
        'title' => 'Опросы',
        'icon' => 'fa fa-bar-chart-o',
        'roles' => [
            'menu.poll' => ['class' => Poll::class],
        ],
    ],
    'page' => [
        'title' => 'Разделы',
        'icon' => 'fa fa-sitemap',
        'roles' => [
            'menu.page' => ['class' => Page::class],
        ],
    ],
    'gallery' => [
        'title' => 'Галереи',
        'icon' => 'fa fa-picture-o',
        'roles' => [
            'menu.gallery' => ['class' => Gallery::class],
        ],
    ],
    'project' => [
        'title' => 'Проекты и события',
        'icon' => 'fa fa-bullhorn',
        'roles' => [
            'menu.project' => ['class' => Project::class],
        ],
    ],
    'service' => [
        'title' => 'Муниципальные услуги',
        'icon' => 'fa fa-flash',
        'roles' => [
            'menu.service' => ['class' => Service::class],
            'menu.serviceSituation' => ['class' => ServiceSituation::class],
            'menu.serviceRubric' => ['class' => ServiceRubric::class],
        ],
        'submenu' => [
            'service' => [
                'title' => 'Услуги',
                'roles' => [
                    'menu.service' => ['class' => Service::class],
                ]
            ],
            'service-situation' => [
                'title' => 'Жизненные ситуации',
                'roles' => [
                    'menu.serviceSituation' => ['class' => ServiceSituation::class],
                ]
            ],
            'service-rubric' => [
                'title' => 'Рубрикатор',
                'roles' => [
                    'menu.serviceRubric' => ['class' => ServiceRubric::class],
                ]
            ],
            'service-target' => [
                'title' => 'Цели',
                'roles' => [
                    'menu.service' => ['class' => Service::class],
                ]
            ],
            'service-complaint-form' => [
                'title' => 'Связи обжалования',
                'roles' => [
                    'menu.service' => ['class' => Service::class],
                ]
            ],
            'appeal' => [
                'title' => 'Обращения',
                'roles' => [
                    'menu.service' => ['class' => Service::class],
                ]
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
        'roles' => [
            'menu.faq' => ['class' => Faq::class],
            'menu.faqCategory' => ['class' => FaqCategory::class],
        ],
        'submenu' => [
            'faq' => [
                'title' => 'Вопросы и ответы',
                'roles' => [
                    'menu.faq' => ['class' => Faq::class],
                ],
            ],
            'faq-category' => [
                'title' => 'Категории',
                'roles' => [
                    'menu.faqCategory' => ['class' => FaqCategory::class],
                ],
            ],
        ],
    ],
    'reserve' => [
        'title' => 'Кадровый резерв',
        'icon' => 'fa fa-address-book',
        'roles' => [
            'menu.form' => ['class' => Form::class],
        ],
        'submenu' => [
            'reserve/profile' => [
                'title' => 'Анкеты',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'reserve/contest' => [
                'title' => 'Голосования',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'reserve/dynamic' => [
                'title' => 'Ход голосования',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'reserve/experts' => [
                'title' => 'Эксперты',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'reserve/list' => [
                'title' => 'Резерв',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
            'reserve/archived' => [
                'title' => 'Архив',
                'roles' => [
                    'menu.form' => ['class' => Form::class],
                ],
            ],
        ],
    ],
    'setting' => [
        'title' => 'Система',
        'icon' => 'fa fa-gears',
        'roles' => [
            'menu.menu' => ['class' => Menu::class],
            'menu.alert' => ['class' => Alert::class],
            'menu.vars' => ['class' => Vars::class],
            'menu.controllerPage' => ['class' => ControllerPage::class],
            'menu.opendata' => ['class' => Opendata::class],
            'menu.statistic' => ['class' => Statistic::class],
            'menu.service' => ['class' => Service::class],
            'menu.application' => ['class' => Application::class],
        ],
        'submenu' => [
            'menu' => [
                'title' => 'Меню',
                'roles' => [
                    'menu.menu' => ['class' => Menu::class],
                ],
            ],
            'alert' => [
                'title' => 'Всплывающие сообщения',
                'roles' => [
                    'menu.alert' => ['class' => Alert::class],
                ],
            ],
            'vars' => [
                'title' => 'Переменные',
                'roles' => [
                    'menu.vars' => ['class' => Vars::class],
                ],
            ],
            'controller-page' => [
                'title' => 'Резервированные пути',
                'roles' => [
                    'menu.controllerPage' => ['class' => ControllerPage::class],
                ],
            ],
            'opendata' => [
                'title' => 'Открытые данные',
                'roles' => [
                    'menu.opendata' => ['class' => Opendata::class],
                ],
            ],
            'statistic' => [
                'title' => 'Статистика',
                'roles' => [
                    'menu.statistic' => ['class' => Statistic::class],
                ]
            ],
            'integrations' => [
                'title' => 'Интеграции',
                'roles' => [
                    'menu.service' => ['class' => Service::class],
                ]
            ],
            'application' => [
                'title' => 'Приложения (API)',
                'roles' => [
                    'menu.application' => ['class' => Application::class],
                ]
            ],
            'box' => [
                'title' => 'Группы',
                'roles' => [
                    'menu.vars' => ['class' => Vars::class], // надо подумать под какие права давать
                ],
            ],
        ],
    ],
];