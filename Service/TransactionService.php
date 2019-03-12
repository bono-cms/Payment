<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Service;

use Payment\Storage\TransactionMapperInterface;
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;

final class TransactionService extends AbstractManager
{
    /**
     * Any compliant transaction mapper
     * 
     * @var \Payment\Storage\TransactionMapperInterface
     */
    private $transactionMapper;

    /**
     * State initialization
     * 
     * @param \Payment\Storage\TransactionMapperInterface $transactionMapper
     * @return void
     */
    public function __construct(TransactionMapperInterface $transactionMapper)
    {
        $this->transactionMapper = $transactionMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setDatetime($row['datetime'])
               ->setAmount($row['amount'])
               ->setStatus($row['status'])
               ->setModule($row['module']);

        return $entity;
    }

    /**
     * Fetch all transactions
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->transactionMapper->fetchAll());
    }
}
