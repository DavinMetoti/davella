<?php

return [
    'items' => [
        [
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'dashboard',
            'permission' => 'Dashboard',
        ],
        [
            'name' => 'Users',
            'icon' => 'fas fa-users',
            'route' => 'users.index',
            'permission' => 'manage users',
        ],
        [
            'name' => 'Menus',
            'icon' => 'fas fa-bars',
            'route' => 'menus.index',
            'permission' => 'manage menus',
        ],
        [
            'name' => 'Cluster',
            'icon' => 'fas fa-home',
            'route' => 'clusters.index',
            'permission' => 'Cluster',
        ],
        [
            'name' => 'Units',
            'icon' => 'fas fa-house-user',
            'route' => 'units.index',
            'permission' => 'Units',
        ],
        [
            'name' => 'Sales Report',
            'icon' => 'fas fa-chart-line',
            'route' => '#',
            'permission' => 'sales report',
        ],
        [
            'name' => 'Reservation',
            'icon' => 'fas fa-calendar-check',
            'route' => 'reservations.index',
            'permission' => 'Reservation',
        ],
    ],
];