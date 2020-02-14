<?php

return [
    '__name' => 'admin-profile',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/admin-profile.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-profile' => ['install','update','remove'],
        'theme/admin/profile' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ],
            [
                'profile' => NULL
            ],
            [
                'lib-form' => NULL
            ],
            [
                'lib-formatter' => NULL
            ],
            [
                'lib-upload' => NULL
            ],
            [
                'lib-pagination' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'AdminProfile\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-profile/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminProfile' => [
                'path' => [
                    'value' => '/profile'
                ],
                'method' => 'GET',
                'handler' => 'AdminProfile\\Controller\\Profile::index'
            ],
            'adminProfileCreate' => [
                'path' => [
                    'value' => '/profile/create',
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::create'
            ],
            'adminProfileEditAccount' => [
                'path' => [
                    'value' => '/profile/(:id)/account',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::account'
            ],
            'adminProfileEditContact' => [
                'path' => [
                    'value' => '/profile/(:id)/contact',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::contact'
            ],
            'adminProfileEditProfile' => [
                'path' => [
                    'value' => '/profile/(:id)/profile',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::profile'
            ],
            'adminProfileEditEducation' => [
                'path' => [
                    'value' => '/profile/(:id)/education',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::education'
            ],
            'adminProfileEditProfession' => [
                'path' => [
                    'value' => '/profile/(:id)/profession',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::profession'
            ],
            'adminProfileEditSocial' => [
                'path' => [
                    'value' => '/profile/(:id)/social',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminProfile\\Controller\\Profile::social'
            ],
            'adminProfileRemove' => [
                'path' => [
                    'value' => '/profile/(:id)/remove',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'AdminProfile\\Controller\\Profile::remove'
            ]
        ]
    ],
    'adminUi' => [
        'sidebarMenu' => [
            'items' => [
                'profile' => [
                    'label' => 'Profile',
                    'icon' => '<i class="fas fa-user-md"></i>',
                    'priority' => 0,
                    'route' => ['adminProfile'],
                    'perms' => 'manage_profile'
                ]
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.profile.index' => [
                'q' => [
                    'label' => 'Search',
                    'type' => 'search',
                    'nolabel' => TRUE,
                    'rules' => []
                ]
            ],
            'admin.profile.account-password' => [
                '@extends' => ['admin.profile.account'],
                'password' => [
                    'label' => 'Password',
                    'type' => 'password',
                    'meter' => true,
                    'rules' => [
                        'length' => [
                            'min' => 6
                        ]
                    ]
                ]
            ],
            'admin.profile.account' => [
                'name' => [
                    'label' => 'Username',
                    'type' => 'text',
                    'slugof' => 'fullname',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'name',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'rules' => [
                        'required' => TRUE,
                        'email' => true,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'email',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'phone' => [
                    'label' => 'Phone',
                    'type' => 'tel',
                    'rules' => [
                        'required' => TRUE,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'phone',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ]
            ],
            'admin.profile.contact' => [
                'contact-email' => [
                    'label' => 'Public Email',
                    'type' => 'email',
                    'rules' => [
                        'email' => true
                    ]
                ],
                'contact-phone' => [
                    'label' => 'Public Phone',
                    'type' => 'tel',
                    'rules' => []
                ],
                'contact-manager' => [
                    'label' => 'Manager Phone',
                    'type' => 'tel',
                    'rules' => []
                ],
                'addr_country' => [
                    'label' => 'Country',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_state' => [
                    'label' => 'State',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_city' => [
                    'label' => 'City',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_street' => [
                    'label' => 'Street',
                    'type' => 'textarea',
                    'rules' => []
                ]
            ],
            'admin.profile.create' => [
                'name' => [
                    'label' => 'Username',
                    'type' => 'text',
                    'slugof' => 'fullname',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'name',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'fullname' => [
                    'label' => 'Fullname',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'rules' => [
                        'required' => TRUE,
                        'email' => true,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'email',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'phone' => [
                    'label' => 'Phone',
                    'type' => 'tel',
                    'rules' => [
                        'required' => TRUE,
                        'unique' => [
                            'model' => 'Profile\\Model\\Profile',
                            'field' => 'phone',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'avatar' => [
                    'label' => 'Avatar',
                    'type' => 'image',
                    'form' => 'std-image',
                    'rules' => [
                        'required' => TRUE,
                        'upload' => TRUE
                    ]
                ],
                'bdate' => [
                    'label' => 'Birth Date',
                    'type' => 'date',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'date' => [
                            'format' => 'Y-m-d'
                        ]
                    ]
                ],
                'bplace' => [
                    'label' => 'Birth Place',
                    'type' => 'text',
                    'rules' => []
                ],
                'gender' => [
                    'label' => 'Gender',
                    'type' => 'select',
                    'rules' => [
                        'enum' => 'profile.gender'
                    ]
                ],
                'height' => [
                    'label' => 'Height ( cm )',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => true 
                    ],
                    'filters' => [
                        'integer' => true
                    ]
                ],
                'weight' => [
                    'label' => 'Weight ( kg )',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => true 
                    ],
                    'filters' => [
                        'integer' => true
                    ]
                ],
                'skin' => [
                    'label' => 'Skin',
                    'type' => 'text',
                    'rules' => []
                ],
                'biography' => [
                    'label' => 'Biography',
                    'type' => 'summernote',
                    'rules' => []
                ],
                'addr_country' => [
                    'label' => 'Country',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_state' => [
                    'label' => 'State',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_city' => [
                    'label' => 'City',
                    'type' => 'text',
                    'rules' => []
                ],
                'addr_street' => [
                    'label' => 'Street',
                    'type' => 'textarea',
                    'rules' => []
                ]
            ],
            'admin.profile.education' => [
                'educations' => [
                    'label' => 'Educations',
                    'type' => 'textarea',
                    'rules' => [
                        'json' => true 
                    ]
                ]
            ],
            'admin.profile.education.local' => [
                'level' => [
                    'label' => 'Level',
                    'type' => 'text',
                    'options' => [
                        'TK' => 'TK',
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'Sarjana' => 'Sarjana',
                        'Magister' => 'Magister',
                        'Doktor' => 'Doktor'
                    ],
                    'rules' => [
                        'required' => true 
                    ]
                ],
                'year' => [
                    'label' => 'Year',
                    'type' => 'month',
                    'rules' => [
                        'required' => true 
                    ]
                ],
                'place' => [
                    'label' => 'Place',
                    'type' => 'text',
                    'rules' => [
                        'required' => true 
                    ]
                ]
            ],
            'admin.profile.profession' => [
                'profession' => [
                    'label' => 'Professions',
                    'type' => 'textarea',
                    'rules' => [
                        'json' => true 
                    ]
                ]
            ],
            'admin.profile.profession.local' => [
                'type' => [
                    'label' => 'Type',
                    'type' => 'text',
                    'options' => [
                        'Model' => 'Model',
                        'DJ' => 'DJ'
                    ],
                    'rules' => [
                        'required' => true 
                    ]
                ],
                'since' => [
                    'label' => 'Since',
                    'type' => 'month',
                    'rules' => [
                        'required' => true 
                    ]
                ]
            ],
            'admin.profile.profile' => [
                'fullname' => [
                    'label' => 'Fullname',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE
                    ]
                ],
                'avatar' => [
                    'label' => 'Avatar',
                    'type' => 'image',
                    'form' => 'std-image',
                    'rules' => [
                        'required' => TRUE,
                        'upload' => TRUE
                    ]
                ],
                'bdate' => [
                    'label' => 'Birth Date',
                    'type' => 'date',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'date' => [
                            'format' => 'Y-m-d'
                        ]
                    ]
                ],
                'bplace' => [
                    'label' => 'Birth Place',
                    'type' => 'text',
                    'rules' => []
                ],
                'gender' => [
                    'label' => 'Gender',
                    'type' => 'select',
                    'rules' => [
                        'required' => TRUE,
                        'enum' => 'profile.gender'
                    ]
                ],
                'height' => [
                    'label' => 'Height ( cm )',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => true 
                    ],
                    'filters' => [
                        'integer' => true
                    ]
                ],
                'weight' => [
                    'label' => 'Weight ( kg )',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => true 
                    ],
                    'filters' => [
                        'integer' => true
                    ]
                ],
                'skin' => [
                    'label' => 'Skin',
                    'type' => 'text',
                    'rules' => []
                ],
                'biography' => [
                    'label' => 'Biography',
                    'type' => 'summernote',
                    'rules' => []
                ]
            ],
            'admin.profile.social' => [
                'socials' => [
                    'label' => 'Socials',
                    'type' => 'textarea',
                    'rules' => [
                        'json' => true 
                    ]
                ]
            ],
            'admin.profile.social.local' => [
                'type' => [
                    'label' => 'Type',
                    'type' => 'text',
                    'options' => [
                        'facebook' => 'facebook',
                        'twitter' => 'twitter',
                        'instagram' => 'instagram',
                        'youtube' => 'youtube',
                        'gplus' => 'gplus'
                    ],
                    'rules' => [
                        'required' => true 
                    ]
                ],
                'url' => [
                    'label' => 'URL',
                    'type' => 'url',
                    'rules' => [
                        'required' => true,
                        'url' => true 
                    ]
                ]
            ]
        ]
    ]
];