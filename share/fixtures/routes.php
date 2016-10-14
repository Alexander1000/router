<?php

return [
    [
        '/',
        [
            'param1' => 'test',
            'param2' => 'test2'
        ]
    ],
    [
        '/test',
        [
            'uri' => '/',
            'action' => 'default',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/test/v2/kkk',
        [
            'uri' => '/v2/kkk',
            'action' => 'kk',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/test/v3/dk',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/test/v4/ts',
        []
    ],
    [
        '/sub',
        []
    ],
    [
        '/sub/34',
        [
            'uri' => '/34'
        ]
    ],
    [
        '/sub/sun',
        [
            'uri' => '/',
            'action' => 'default',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/sub/sun/v2/kkk',
        [
            'uri' => '/v2/kkk',
            'action' => 'kk',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/sub/sun/v3/dk',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/sub/sun/v4/ts',
        []
    ],
    [
        '/jj',
        []
    ],
    [
        '/sub/sun/v3/dk?test=1',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    [
        '/test/user/123/edit',
        [
            'action' => 'updateUser',
            'controller' => 'UserController',
            'params' => [
                'userId' => 123
            ]
        ]
    ],
    [
        '/shop/shop-name',
        [
            'action' => 'default',
            'controller' => 'AdminController',
            'params' => [
                'shopName' => 'shop-name'
            ]
        ]
    ],
    [
        '/shop/shop-name/user/934/edit',
        [
            'action' => 'updateUser',
            'controller' => 'UserController',
            'params' => [
                'shopName' => 'shop-name',
                'userId' => 934
            ]
        ]
    ]
];
