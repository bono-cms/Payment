<?php

namespace Payment\Storage;

interface TransactionMapperInterface
{
    /**
     * Fetch all transactions
     * 
     * @return array
     */
    public function fetchAll();
}
