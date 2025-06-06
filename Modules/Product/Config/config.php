<?php

return [
    'name' => 'Product',
    'ARRAY_MENU' => [
        [
            'title' => 'sidebar.shop',
            'menu_item_type' => 'static',
            'permission' => ['view_product'],
            'order' => 8,
        ],
        [
            'start_icon' => 'fa-solid fa-store',
            'title' => 'sidebar.product',
            'menu_item_type' => 'parent',
            'route' => 'backend.products.index',
            'permission' => ['view_product'],
            'order' => 8,
            'children' => [
                [
                    'title' => 'sidebar.all_product',
                    'route' => 'backend.products.index',
                    'active' => 'app/products',
                    'permission' => ['view_product'],
                    'order' => 0,
                ],
                [
                    'title' => 'sidebar.brand',
                    'route' => 'backend.brands.index',
                    'active' => 'app/brands',
                    'permission' => ['view_brand'],
                    'order' => 1,
                ],
                [
                    'title' => 'sidebar.categories',
                    'route' => 'backend.products-categories.index',
                    'active' => 'app/products-categories',
                    'permission' => ['view_product_category'],
                    'order' => 2,
                ],
                [
                    'title' => 'sidebar.sub_categories',
                    'route' => 'backend.products-categories.index_nested',
                    'active' => 'app/products-sub-categories',
                    'permission' => ['view_product_category'],
                    'order' => 3,
                ],
                [
                    'title' => 'sidebar.units',
                    'route' => 'backend.units.index',
                    'active' => 'app/units',
                    'permission' => ['view_product_units'],
                    'order' => 4,
                ],
                [
                    'title' => 'sidebar.tag',
                    'route' => 'backend.tags.index',
                    'active' => 'app/tags',
                    'permission' => ['view_product_tags'],
                    'order' => 5,
                ],
            ],
        ],
        [
            'start_icon' => 'fa-solid fa-swatchbook',
            'title' => 'sidebar.variations',
            'route' => 'backend.variations.index',
            'active' => 'app/variations',
            'permission' => ['view_product_variations'],
            'order' => 8,
        ],
        [
            'start_icon' => 'fa-solid fa-bag-shopping',
            'title' => 'sidebar.orders',
            'route' => 'backend.orders.index',
            'active' => 'app/orders',
            'permission' => ['view_product_orders'],
            'order' => 8,
        ],
        [
            'start_icon' => 'fa-solid fa-chart-pie',
            'title' => 'sidebar.orders_report',
            'route' => 'backend.reports.order-report',
            'active' => 'app/order-report',
            'permission' => ['view_report'],
            'order' => 18,
        ],


        [

            'start_icon' => 'fa-solid fa-truck-field',
            'title' => 'sidebar.supply',
            'menu_item_type' => 'parent',
            'route' => 'backend.products.index',
            'permission' => ['view_logistics','view_shipping_zone'],
            'order' => 8,
            'children' => [
                [
                    'title' => 'sidebar.logistics',
                    'route' => 'backend.logistics.index',
                    'active' => 'app/logistics',
                    'permission' => ['view_logistics'],
                    'order' => 0,
                ],
                [
                    'title' => 'sidebar.logistic_zone',
                    'route' => 'backend.logistic-zones.index',
                    'active' => 'app/logistic-zones',
                    'permission' => ['view_shipping_zone'],
                    'order' => 1,
                ],
            ],
        ],




    ],
    'H_ARRAY_MENU' => [
        [
            'menu_type' => 'horizontal',
            'title' => 'sidebar.shop',
            'menu_item_type' => 'parent',
            'permission' => ['view_product'],
            'order' => 0,
            'children' => [
                [
                    'start_icon' => 'fa-solid fa-store',
                    'title' => 'sidebar.product',
                    'menu_item_type' => 'parent',
                    'route' => 'backend.products.index',
                    'permission' => ['view_product'],
                    'order' => 6,
                    'children' => [
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.list',
                            'route' => 'backend.products.index',
                            'permission' => ['view_product'],
                            'active' => 'app/products',
                            'order' => 0,
                        ],
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.brand',
                            'route' => 'backend.brands.index',
                            'active' => 'app/brands',
                            'permission' => ['view_brand'],
                            'order' => 1,
                        ],
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.categories',
                            'route' => 'backend.products-categories.index',
                            'active' => 'app/products-categories',
                            'permission' => ['view_product_category'],
                            'order' => 1,
                        ],
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.sub_categories',
                            'route' => 'backend.products-categories.index_nested',
                            'active' => 'app/products-sub-categories',
                            'permission' => ['view_product_category'],
                            'order' => 2,
                        ],
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.units',
                            'route' => 'backend.units.index',
                            'active' => 'app/units',
                            'permission' => ['view_product_units'],
                            'order' => 3,
                        ],
                        [
                            'menu_type' => 'horizontal',
                            'title' => 'sidebar.tag',
                            'route' => 'backend.tags.index',
                            'active' => 'app/tags',
                            'permission' => ['view_product_tags'],
                            'order' => 4,
                        ],
                    ],
                ],
                [
                    'menu_type' => 'horizontal',
                    'start_icon' => 'fa-solid fa-swatchbook',
                    'title' => 'sidebar.variations',
                    'route' => 'backend.variations.index',
                    'permission' => ['view_product_variations'],
                    'active' => 'app/variations',
                    'permission' => ['view_product_variations'],
                    'order' => 6,
                ],
                [
                    'menu_type' => 'horizontal',
                    'start_icon' => 'fa-solid fa-bag-shopping',
                    'title' => 'sidebar.orders',
                    'route' => 'backend.orders.index',
                    'permission' => ['view_product_orders'],
                    'active' => 'app/orders',
                    'permission' => ['view_product_orders'],
                    'order' => 6,
                ],
                [
                    'menu_type' => 'horizontal',
                    'start_icon' => 'fa-solid fa-truck-field',
                    'title' => 'sidebar.supply',
                    'menu_item_type' => 'parent',
                    'route' => 'backend.products.index',
                    'permission' => ['view_logistics','view_shipping_zone'],
                    'order' => 6,
                    'children' => [
                        [
                            'title' => 'sidebar.logistics',
                            'route' => 'backend.logistics.index',
                            'active' => 'app/logistics',
                            'permission' => ['view_logistics'],
                            'order' => 0,
                        ],
                        [
                            'title' => 'sidebar.logistic_zone',
                            'route' => 'backend.logistic-zones.index',
                            'active' => 'app/logistic-zones',
                            'permission' => ['view_shipping_zone'],
                            'order' => 1,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
