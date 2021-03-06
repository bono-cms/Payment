<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Payment\Storage\TransactionMapperInterface;

final class TransactionMapper extends AbstractMapper implements TransactionMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_payment_transactions');
    }

    /**
     * Updates transaction status by its token
     * 
     * @param string $token Unique transaction token
     * @param int $status New status constant
     * @return boolean Depending on success
     */
    public function updateStatusByToken($token, $status)
    {
        $db = $this->db->update(self::getTableName(), array('status' => $status))
                       ->whereEquals('token', $token);

        return (bool) $db->execute(true);
    }

    /**
     * Finds transaction by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token)
    {
        return $this->fetchByColumn('token', $token);
    }

    /**
     * Fetch all transactions
     * 
     * @return array
     */
    public function fetchAll()
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
