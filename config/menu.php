<?php

return [
    'sidebar' => [
        /** SAMPLE AVAILABLE PARAMETER
        [
            'type' => 'tree', // 'group' / 'tree' / 'heading' / 'single'
            'label' => 'Menu Title',
            'icon' => 'fa fa-home',
            'url' => '/',
            'active' => '\View::shared("menu_active") == "user"', // cukup taruh di child nya aja, parent otomatis
            'children' => [],
            'required_configs' => [1,2], // kalau parent tidak ada ketentuan khusus cukup taruh di child nya aja
            'required_configs_rule' => 'or',
            'required_features' => [1,2], // kalau parent tidak ada ketentuan khusus cukup taruh di child nya aja
            'required_features_rule' => 'or',
        ],
        */
        [
            'type' => 'group',
            'label' => 'Home',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Home',
                    'icon' => 'icon-home',
                    'url' => 'home',
                    'active' => '\View::shared("menu_active") == "home"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'User',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'User',
                    'icon' => 'icon-user',
                    'url' => 'user',
                    'active' => '\View::shared("menu_active") == "user"',
                ],
                // [
                //     'type' => 'single',
                //     'label' => 'Doctor Shift',
                //     'icon' => 'icon-clock',
                //     'url' => 'doctor-shift',
                //     'active' => '\View::shared("menu_active") == "doctor-shift"',
                // ],
                [
                    'type' => 'single',
                    'label' => 'Doctor Schedule',
                    'icon' => 'icon-user',
                    'url' => 'doctor-schedule',
                    'active' => '\View::shared("menu_active") == "doctor-schedule"',
                ],
                // [
                //     'type' => 'single',
                //     'label' => 'Doctor Schedule Date',
                //     'icon' => 'icon-user',
                //     'url' => 'doctor-schedule-date',
                //     'active' => '\View::shared("menu_active") == "doctor-schedule-date"',
                // ],
                // [
                //     'type' => 'single',
                //     'label' => 'Employee Schedule',
                //     'icon' => 'icon-user',
                //     'url' => 'user',
                //     'active' => '\View::shared("menu_active") == "employee-schedule"',
                // ],
                // [
                //     'type' => 'single',
                //     'label' => 'Employee Schedule date',
                //     'icon' => 'icon-user',
                //     'url' => 'user',
                //     'active' => '\View::shared("menu_active") == "employee-schedule-date"',
                // ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Outlet',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Outlet',
                    'icon' => 'fa fa-bank',
                    'url' => 'outlet',
                    'active' => '\View::shared("menu_active") == "outlet"',
                ]
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Product',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Product',
                    'icon' => 'fa fa-file-o',
                    'url' => 'product',
                    'active' => '\View::shared("menu_active") == "product"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Treatment',
                    'icon' => 'fa fa-file-text-o',
                    'url' => 'treatment',
                    'active' => '\View::shared("menu_active") == "treatment"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Package',
                    'icon' => 'fa fa-file-powerpoint-o',
                    'url' => 'product_package',
                    'active' => '\View::shared("menu_active") == "product_package"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Landing Page',
            'required_features' => [],
            'children' => [
                [
                    'label' => 'Landing Page Home',
                    'required_features' => [],
                    'type' => 'tree',
                    'icon' => 'fa fa-university',
                    'children' => [
                        [
                            'label' => 'Banner',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "banner"',
                            'url' => 'banner'
                        ],
                        [
                            'label' => 'Product Finest',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "product_finest"',
                            'url' => 'landing_page/home/product_finest'
                        ],
                        [
                            'label' => 'Official Partner',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "official_partner_home"',
                            'url' => 'landing_page/home/official_partner'
                        ],
                        [
                            'label' => 'Treatment & Consultation',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "treatment_and_consultation"',
                            'url' => 'landing_page/home/treatment_and_consultation'
                        ],
                        [
                            'label' => 'Product Trending',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "product_trending"',
                            'url' => 'landing_page/home/product_trending'
                        ],
                        [
                            'label' => 'Article Recommendation',
                            'required_features' => '',
                            'active' => '\View::shared("submenu_active") == "article_recommendation"',
                            'url' => 'landing_page/home/article_recommendation'
                        ],
                    ],
                ],
                [
                    'type' => 'single',
                    'label' => 'Banner Clinic',
                    'icon' => 'fa fa-file-image-o',
                    'url' => 'landing_page/banner_clinic',
                    'active' => '\View::shared("menu_active") == "banner_clinic"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Contact Official',
                    'icon' => 'fa fa-comments-o',
                    'url' => 'landing_page/contact_official',
                    'active' => '\View::shared("menu_active") == "contact_official"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Consultation & Ordering',
                    'icon' => 'fa fa-book',
                    'url' => 'landing_page/consultation_ordering',
                    'active' => '\View::shared("menu_active") == "contasultation_ordering"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Official Partner',
                    'icon' => 'fa fa-columns',
                    'url' => 'landing_page/official_partner',
                    'active' => '\View::shared("menu_active") == "official_partner"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Article',
                    'icon' => 'fa fa-clone',
                    'url' => 'article',
                    'active' => '\View::shared("menu_active") == "article"',
                ],
                [
                    'type' => 'single',
                    'label' => 'Contact Message',
                    'icon' => 'fa fa-comments-o',
                    'url' => 'landing_page/contact_message',
                    'active' => '\View::shared("menu_active") == "contact_message"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Customer',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Customer',
                    'icon' => 'fa fa-users',
                    'url' => 'customer',
                    'active' => '\View::shared("menu_active") == "customer"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Partner',
            'required_features' => [],
            'children' => [
                // [
                //     'type' => 'single',
                //     'label' => 'Partner',
                //     'icon' => 'fa fa-info-circle',
                //     'url' => 'partner',
                //     'active' => '\View::shared("menu_active") == "partner"',
                // ],
                [
                    'type' => 'single',
                    'label' => 'Partner',
                    'icon' => 'fa fa-user-md',
                    'url' => 'partner_equal',
                    'active' => '\View::shared("menu_active") == "partner_equal"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Grievance',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Grievance',
                    'icon' => 'fa fa-pencil-square',
                    'url' => 'grievance',
                    'active' => '\View::shared("menu_active") == "grievance"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Diagnostic',
            'required_features' => [],
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Diagnostic',
                    'icon' => 'fa fa-stethoscope',
                    'url' => 'diagnostic',
                    'active' => '\View::shared("menu_active") == "diagnostic"',
                ],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Transaction',
            'children' => [
                [
                    'type' => 'single',
                    'label' => 'Order',
                    'icon' => 'fa fa-list',
                    'url' => 'order',
                    'active' => '\View::shared("menu_active") == "order"',
                ],
                // [
                    // 'type' => 'tree',
                    // 'label' => 'Settings',
                    // 'icon' => 'fa fa-cogs',
                    // 'children' => [
                        // [
                        //     'label' => 'Price Setting',
                        //     'required_features' => [32, 34, 37],
                        //     'active' => '\View::shared("submenu_active") == "setting-price"',
                        //     'url' => 'transaction/setting/price'
                        // ],
                        // [
                        //     'label' => 'Available Payment Method',
                        //     'required_features' => [30, 31],
                        //     'active' => '\View::shared("submenu_active") == "setting-payment-method"',
                        //     'url' => 'transaction/setting/available-payment'
                        // ],
                    // ]
                // ]
            ]
        ],
        // [
        //     'type' => 'group',
        //     'label' => 'Browse',
        //     'children' => [
        //         [
        //             'label' => 'Vehicle',
        //             'required_features' => [5,6,7,8],
        //             'active' => '\View::shared("menu_active") == "browse-vehicle"',
        //             'url' => 'browse/vehicle',
        //             'icon' => 'fa fa-car'
        //         ],
        //         [
        //             'label' => 'Location',
        //             'required_features' => [1,2,3,4],
        //             'active' => '\View::shared("menu_active") == "browse-location"',
        //             'url' => 'browse/location',
        //             'icon' => 'fa fa-map'
        //         ],
        //         [
        //             'label' => 'ECS',
        //             'required_features' => [],
        //             'type' => 'tree',
        //             'children' => [
        //                 [
        //                     'label' => 'Device',
        //                     'required_features' => [28,29],
        //                     'active' => '\View::shared("menu_active") == "browse-ecs"',
        //                     'url' => 'browse/ecs',
        //                 ],
        //                 [
        //                     'label' => 'Pricing',
        //                     'required_features' => [28,29],
        //                     'active' => '\View::shared("menu_active") == "pricing-ecs"',
        //                     'url' => 'browse/ecs/pricing',
        //                 ],
        //             ],
        //             'icon' => 'fa fa-plug'
        //         ],
        //         [
        //             'label' => 'Apply For Casion',
        //             'required_features' => [9,10],
        //             'active' => '\View::shared("menu_active") == "browse-apply-casion"',
        //             'url' => 'browse/apply-casion',
        //             'icon' => 'fa fa-star'
        //         ],
        //         [
        //             'label' => 'Mobile Users',
        //             'required_features' => [11,12],
        //             'active' => '\View::shared("menu_active") == "browse-mobile-user"',
        //             'url' => 'browse/mobile-user',
        //             'icon' => 'fa fa-users'
        //         ],
        //     ]
        // ],
        [
            'type' => 'group',
            'label' => 'Settings',
            'children' => [
                [
                    'label' => 'Commision Doctor Global',
                    'required_features' => [13,14],
                    'active' => '\View::shared("menu_active") == "commission-doctor-global"',
                    'url' => 'setting/commission-doctor-global',
                    'icon' => 'fa fa-chain'
                ],
                [
                    'label' => 'Mobile Apps Home',
                    'required_features' => [],
                    'type' => 'tree',
                    'children' => [
                        [
                            'label' => 'Splash Screen',
                            'required_features' => [26,27],
                            'active' => '\View::shared("submenu_active") == "splash-screen"',
                            'url' => 'setting/splash-screen'
                        ],
                    ],
                    'icon' => 'icon-screen-tablet '
                ],
                [
                    'label' => 'Version Control',
                    'required_features' => [13,14],
                    'active' => '\View::shared("menu_active") == "setting-version"',
                    'url' => 'setting/version',
                    'icon' => 'fa fa-info-circle'
                ],
                // [
                //     'label' => 'Intro Apps',
                //     'required_features' => [19,20,21],
                //     'active' => '\View::shared("submenu_active") == "on-boarding"',
                //     'url' => 'setting/on-boarding',
                //     'icon' => 'icon-screen-tablet'
                // ],
                // [
                //     'label' => 'FAQ',
                //     'required_features' => [15,16,17,18],
                //     'active' => '\View::shared("submenu_active") == "faq"',
                //     'url' => 'setting/faq',
                //     'icon' => 'icon-question'
                // ],
                // [
                //     'label' => 'Privacy Policy',
                //     'required_features' => [22,23],
                //     'active' => '\View::shared("submenu_active") == "privacy-policy"',
                //     'url' => 'setting/privacy-policy',
                //     'icon' => 'fa fa-lock'
                // ],
                // [
                //     'label' => 'Terms of Service',
                //     'required_features' => [24,25],
                //     'active' => '\View::shared("submenu_active") == "terms-of-service"',
                //     'url' => 'setting/terms-of-service',
                //     'icon' => 'fa fa-check-square-o'
                // ],
                // [
                //     'label' => 'Auto-Response',
                //     'required_features' => [40,41],
                //     'active' => '\View::shared("menu_active") == "setting-autoresponse"',
                //     'url' => 'setting/autoresponse',
                //     'icon' => 'fa fa-bullhorn'
                // ],

                // [
                //     'label' => 'Maintenance Mode',
                //     'required_features' => [],
                //     'url' => 'setting/maintenance-mode',
                //     'icon' => 'icon-wrench'
                // ],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Super Admin',
            'children' => [
                [
                    'label' => 'CMS Users',
                    'required_features' => ['super_admin'],
                    'active' => '\View::shared("menu_active") == "browse-cms-user"',
                    'url' => 'browse/cms-user',
                    'icon' => 'fa fa-user'
                ],
                [
                    'label' => 'Role',
                    'required_features' => ['super_admin'],
                    'active' => '\View::shared("menu_active") == "browse-role"',
                    'url' => 'browse/role',
                    'icon' => 'fa fa-asterisk'
                ],
            ],
        ],
    ],
];
