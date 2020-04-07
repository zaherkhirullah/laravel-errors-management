<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'record_error' => Hayrullah\ErrorManagement\Models\RecordError::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'visits' => Hayrullah\ErrorManagement\Models\Visit::class,

        'has_visits' => Hayrullah\ErrorManagement\Traits\HasVisits::class,

    ],

    'table_names' => [

        'record_errors' => 'record_errors',
        'visits' => 'visits',

    ],

    'codes' => [
        '401' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation',
            'bg-color' => 'bg-warning',
            'color' => 'warning',
            'code' => 401,
        ],
        '403' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation-circle',
            'bg-color' => 'bg-blue',
            'color' => 'blue',
            'code' => 403,
        ],
        '404' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation-triangle',
            'bg-color' => 'bg-danger',
            'color' => 'danger',
            'code' => 404,
        ],
        '419' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation-circle',
            'bg-color' => 'bg-secondary',
            'color' => 'secondary',
            'code' => 419,
        ],
        '429' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation-circle',
            'bg-color' => 'bg-dark',
            'color' => 'dark',
            'code' => 429,
        ],
        '500' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation-triangle',
            'bg-color' => 'bg-danger',
            'color' => 'danger',
            'code' => 500,
        ],
        '503' => [
            'active' => true,
            'icon' => 'fas fa-fas fa-fw fa-exclamation',
            'bg-color' => 'bg-info',
            'color' => 'info',
            'code' => 503,
        ],
    ],
];
