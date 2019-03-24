<?php

namespace Payment\Storage;

interface TransactionMapperInterface
{
    /**
     * Updates transaction status by its token
     * 
     * @param string $token Unique transaction token
     * @param int $status New status constant
     * @return boolean
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
