<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Storage;

interface TransactionMapperInterface
{
    /**
     * Updates transaction status by its token
     * 
     * @param string $token Unique transaction token
     * @param int $status New status constant
     * @return boolean Depending on success
     */
    public function updateStatusByToken($token, $status);

    /**
     * Finds transaction by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token);

    /**
     * Fetch all transactions
     * 
     * @return array
     */
    public function fetchAll();
}
