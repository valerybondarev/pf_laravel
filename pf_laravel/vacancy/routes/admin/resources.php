<?php

Route::group(['middleware' => ['admin.auth']], function () {
    Route::resources([
        'users'      => 'Users\UserController',
        'categories' => 'Catalog\CategoryController',
        'clients'    => 'Client\ClientController',
        'mailings'   => 'Mailing\MailingController',
        'vacancies'  => 'Vacancy\VacancyController',
    ]);
});
