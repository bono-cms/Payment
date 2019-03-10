<?php

/**
 * Module configuration container
 */

return array(
    'caption' => 'Payment',
    'description' => 'Payment module handles all transactions on your site',
    // Bookmarks of this module
    'bookmarks' => array(
        array(
            'name' => 'Recent transactions',
            'controller' => 'Payment:Admin:Transaction@indexAction',
            'icon' => 'fas fa-credit-card'
        )
    ),
    'menu' => array(
        'name' => 'Payment',
        'icon' => 'fas fa-credit-card',
        'items' => array(
            array(
                'route' => 'Payment:Admin:Transaction@indexAction',
                'name' => 'View all transactions'
            )
        )
    )
);