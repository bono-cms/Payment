<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment;

use Cms\AbstractCmsModule;
use Payment\Service\TransactionService;

final class Module extends AbstractCmsModule
{
    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        return array(
            'transactionService' => new TransactionService($this->getMapper('\Payment\Storage\MySQL\TransactionMapper'))
        );
    }
}