<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Controller;

use Site\Controller\AbstractController;

/**
 * Main payment controller, that handles all transactions
 */
final class Payment extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function bootstrap($action)
    {
        // Disabled CSRF for gateway action
        if ($action === 'successAction') {
            $this->enableCsrf = false;
        }

        parent::bootstrap($action);
    }

    /**
     * Handle success or failure after payment gets done
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function successAction($token)
    {
        
    }

    /**
     * Invokes gateway by its associated token
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function gatewayAction($token)
    {
        
    }

    /**
     * Creates new transaction
     * 
     * @return string
     */
    public function newAction()
    {
        
    }
}
