<?php

return [
    // #0
    [
        'get',
        '/',
        [
            'param1' => 'test',
            'param2' => 'test2'
        ]
    ],
    // #1
    [
        'get',
        '/test',
        [
            'uri' => '/',
            'action' => 'default',
            'controller' => 'AdminController'
        ]
    ],
    // #2
    [
        'get',
        '/test/v2/kkk',
        [
            'uri' => '/v2/kkk',
            'action' => 'kk',
            'controller' => 'AdminController'
        ]
    ],
    // #3
    [
        'get',
        '/test/v3/dk',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    // #6
    [
        'get',
        '/sub/34',
        [
            'uri' => '/34'
        ]
    ],
    // #7
    [
        'get',
        '/sub/sun',
        [
            'uri' => '/',
            'action' => 'default',
            'controller' => 'AdminController'
        ]
    ],
    // #8
    [
        'get',
        '/sub/sun/v2/kkk',
        [
            'uri' => '/v2/kkk',
            'action' => 'kk',
            'controller' => 'AdminController'
        ]
    ],
    // #9
    [
        'get',
        '/sub/sun/v3/dk',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    // #12
    [
        'get',
        '/sub/sun/v3/dk?test=1',
        [
            'uri' => '/v3/dk',
            'action' => 'dk',
            'controller' => 'AdminController'
        ]
    ],
    // #13
    [
        'put',
        '/test/user/123/edit',
        [
            'action' => 'updateUser',
            'controller' => 'UserController',
            'params' => [
                'userId' => 123
            ]
        ]
    ],
    // #14
    [
        'put',
        '/shop/shop-name',
        [
            'action' => 'default',
            'controller' => 'AdminController',
            'params' => [
                'shopName' => 'shop-name'
            ]
        ]
    ],
    // #15
    [
        'put',
        '/shop/shop-name/user/934/edit',
        [
            'action' => 'updateUser',
            'controller' => 'UserController',
            'params' => [
                'shopName' => 'shop-name',
                'userId' => 934
            ]
        ]
    ],
    // #16
    [
        'post',
        '/',
        [
            'param1' => 'test',
            'param2' => 'test2'
        ]
    ],
];
