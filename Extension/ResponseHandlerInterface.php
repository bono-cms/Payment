<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Extension;

interface ResponseHandlerInterface
{
    /**
     * Checkes whether user canceled transaction
     * 
     * @return boolean
     */
    public function canceled();

    /**
     * Checkes whether transaction was successful
     * 
     * @return boolean
     */
    public function successful();
}
