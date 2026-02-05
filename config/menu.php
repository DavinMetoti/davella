<?php

return [
    'items' => [
        [
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'dashboard',
            'permission' => null, // or specific permission
        ],
        [
            'name' => 'Users',
            'icon' => 'fas fa-users',
            'route' => 'users.index', // assume route exists
            'permission' => 'manage users',
        ],
        [
            'name' => 'Companies',
            'icon' => 'fas fa-building',
            'route' => 'companies.index',
            'permission' => 'manage companies',
        ],
        [
            'name' => 'Menus',
            'icon' => 'fas fa-bars',
            'route' => 'menus.index',
            'permission' => 'manage menus',
        ],
        [
            'name' => 'Clusters',
            'icon' => 'fas fa-home',
            'route' => 'clusters.index',
            'permission' => 'manage clusters',
        ],
        [
            'name' => 'Units',
            'icon' => 'fas fa-house-user',
            'route' => 'units.index',
            'permission' => 'manage units',
        ],
    ],
];