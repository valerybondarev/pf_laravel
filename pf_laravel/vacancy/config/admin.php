<?php

use App\Domain\Authentication\Enums\Permission;

return [


    /*
    |--------------------------------------------------------------------------
    | Logo path
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'logo'       => '/admin/static/images/logo.png',

    /*
    |--------------------------------------------------------------------------
    | Background class
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'background' => 'bg-primary',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'sidebar'    => [
        //'demo'   => [
        //    'label' => 'admin.menu.demo',
        //    'items' => [
        //        'index' => [
        //            'route' => 'admin.demo',
        //            'label' => 'admin.menu.demo',
        //            'icon'  => '<i class="fa fa-desktop text-primary"></i>',
        //        ],
        //    ],
        //],
        'common' => [
            'items' => [
                'clients'          => [
                    'route' => 'admin.clients.index',
                    'label' => 'Клиенты',
                    'icon'  => '<i class="ni ni-bullet-list-67 text-primary"></i>',
                ],
                'mailings'         => [
                    'route' => 'admin.mailings.index',
                    'label' => 'admin.menu.mailing',
                    'icon'  => '<i class="ni ni-world-2 text-primary"></i>',
                ],

            ],
        ],
        'sklad'  => [
            'items' => [
                'category'    => [
                    'route' => 'admin.categories.index',
                    'label' => 'Категории вакансий',
                    'icon'  => '<i class="ni ni-bold-right text-primary"></i>',
                ],
                'vacancy'    => [
                    'route' => 'admin.vacancies.index',
                    'label' => 'admin.menu.vacancy',
                    'icon'  => '<i class="ni ni-bold-right text-primary"></i>',
                ],
                'toSite'      => [
                    'route' => 'web.index',
                    'label' => 'admin.menu.toSite',
                    'icon'  => '<i class="ni ni-world-2 text-primary"></i>',
                ],
            ],
        ],
        'system' => [
            'permissions' => Permission::SYSTEM,
            'label'       => 'admin.menu.system',
            'items'       => [
                'users' => [
                    'route' => 'admin.users.index',
                    'label' => 'admin.menu.users',
                    'icon'  => '<i class="ni ni-circle-08 text-primary"></i>',
                ],
            ],
        ],
    ],

];
