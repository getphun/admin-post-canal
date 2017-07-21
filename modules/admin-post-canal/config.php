<?php
/**
 * admin-post-canal config file
 * @package admin-post-canal
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-post-canal',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-post-canal',
    '__files' => [
        'modules/admin-post-canal'  => [ 'install', 'remove', 'update' ],
        'theme/admin/post/canal'    => [ 'install', 'remove', 'update' ]
    ],
    '__dependencies' => [
        'admin',
        'post-canal'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'AdminPostCanal\\Controller\\CanalController' => 'modules/admin-post-canal/controller/CanalController.php'
        ],
        'files' => []
    ],
    '_routes' => [
        'admin' => [
            'adminPostCanal' => [
                'rule' => '/post/canal',
                'handler' => 'AdminPostCanal\\Controller\\Canal::index'
            ],
            'adminPostCanalEdit' => [
                'rule'  => '/post/canal/:id',
                'handler' => 'AdminPostCanal\\Controller\\Canal::edit'
            ],
            'adminPostCanalFilter' => [
                'rule'  => '/post/canal/filter',
                'handler' => 'AdminPostCanal\\Controller\\Canal::filter'
            ],
            'adminPostCanalRemove' => [
                'rule'  => '/post/canal/:id/remove',
                'handler' => 'AdminPostCanal\\Controller\\Canal::remove'
            ]
        ]
    ],
    
    'admin' => [
        'menu' => [
            'post' => [
                'label'     => 'Post',
                'icon'      => 'newspaper-o',
                'order'     => 10,
                'submenu'   => [
                    'post-canal'  => [
                        'label'     => 'Canal',
                        'perms'     => 'read_post_canal',
                        'target'    => 'adminPostCanal',
                        'order'     => 50
                    ]
                ]
            ]
        ]
    ],
    
    'form' => [
        'admin-post-canal' => [
            'name' => [
                'type'      => 'text',
                'label'     => 'Name',
                'rules'     => [
                    'required'  => true
                ]
            ],
            'slug' => [
                'type'      => 'text',
                'label'     => 'Slug',
                'attrs'     => [
                    'data-slug' => '#field-name'
                ],
                'rules'     => [
                    'required'  => true,
                    'alnumdash' => true,
                    'unique' => [
                        'model' => 'PostCanal\\Model\\PostCanal',
                        'field' => 'slug',
                        'self'  => [
                            'uri'   => 'id',
                            'field' => 'id'
                        ]
                    ]
                ]
            ],
            'about' => [
                'type'      => 'textarea',
                'label'     => 'About',
                'rules'     => []
            ],
            'meta_title' => [
                'type'      => 'text',
                'label'     => 'Meta Title',
                'rules'     => []
            ],
            'meta_description' => [
                'type'      => 'textarea',
                'label'     => 'Meta Description',
                'rules'     => []
            ],
            'meta_keywords' => [
                'type'      => 'text',
                'label'     => 'Meta Keywords',
                'rules'     => []
            ]
        ],
        'admin-post-canal-index' => [
            'q' => [
                'type' => 'search',
                'label'=> 'Find canal',
                'nolabel'=> true,
                'rules'=> []
            ]
        ]
    ]
];