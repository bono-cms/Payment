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

use Payment\Collection\StatusCollection;
use Payment\Storage\TransactionMapperInterface;
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Date\TimeHelper;
use Krystal\Text\TextUtils;

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
               ->setModule($row['module'])
               ->setPaymentSystem($row['payment_system'])
               ->setToken($row['token']);

        return $entity;
    }

    /**
     * Adds new transaction
     * 
     * @param float $amount Charged amount
     * @param string $module
     * @param string $paymentSystem Payment system name used to perform a transaction
     * @return boolean
     */
    public function add($amount, $module, $paymentSystem)
    {
        // Data to be inserted
        $data = array(
            'datetime' => TimeHelper::getNow(),
            'amount' => $amount,
            'module' => $module,
            'payment_system' => $paymentSystem,
            'status' => StatusCollection::PARAM_STATUS_TEMPORARY,
            'token' => TextUtils::uniqueString()
        );

        return $this->transactionMapper->persist($data);
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
