<?php

return array(
    '/%s/module/payment' => array(
        'controller' => 'Admin:Transaction@indexAction'
    ),

    '/%s/module/payment/save' => array(
        'controller' => 'Admin:Transaction@saveAction'
    ),

    '/%s/module/payment/add' => array(
        'controller' => 'Admin:Transaction@addAction'
    ),

    '/%s/module/payment/edit/(:var)' => array(
        'controller' => 'Admin:Transaction@editAction'
    ),

    '/%s/module/payment/delete/(:var)' => array(
        'controller' => 'Admin:Transaction@deleteAction'
    ),

    '/%s/module/payment/notify/(:var)' => array(
        'controller' => 'Admin:Transaction@notifyAction'
    ),

    '/payment/transaction/new' => array(
        'controller' => 'Payment@newAction'
    ),

    '/payment/transaction/gateway/(:var)' => array(
        'controller' => 'Payment@gatewayAction'
    ),

    '/payment/transaction/response/(:var)' => array(
        'controller' => 'Payment@responseAction'
    )
);