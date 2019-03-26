<?php

return array(
    '/%s/module/payment' => array(
        'controller' => 'Admin:Transaction@indexAction'
    ),

    '/%s/module/payment/delete/(:var)' => array(
        'controller' => 'Admin:Transaction@deleteAction'
    ),

    '/payment/transaction/new/(:var)' => array(
        'controller' => 'Payment@newAction'
    ),

    '/payment/transaction/gateway/(:var)' => array(
        'controller' => 'Payment@gatewayAction'
    ),

    '/payment/transaction/success/(:var)' => array(
        'controller' => 'Payment@successAction'
    )
);