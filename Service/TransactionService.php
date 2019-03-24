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
               ->setPayer($row['payer'])
               ->setDatetime($row['datetime'])
               ->setAmount($row['amount'])
               ->setCurrency($row['currency'])
               ->setStatus($row['status'])
               ->setModule($row['module'])
               ->setPaymentSystem($row['payment_system'])
               ->setToken($row['token']);

        return $entity;
    }

    /**
     * Confirms that payment is done by token
     * 
     * @param string $token Transaction token
     * @return boolean Depending on success
     */
    public function confirmPayment(string $token)
    {
        return $this->invoiceMapper->updateStatusByToken($token, StatusCollection::PARAM_STATUS_COMPLETE);
    }

    /**
     * Adds new transaction
     * 
     * @param string $payer Payer name
     * @param float $amount Charged amount
     * @param string $currency Payment currency
     * @param string $module
     * @param string $paymentSystem Payment system name used to perform a transaction
     * @return string (Returns unique transaction token)
     */
    public function add($payer, $amount, $currency, $module, $paymentSystem)
    {
        $token = TextUtils::uniqueString();

        // Data to be inserted
        $data = array(
            'datetime' => TimeHelper::getNow(),
            'payer' => $payer,
            'amount' => $amount,
            'currency' => $currency,
            'module' => $module,
            'payment_system' => $paymentSystem,
            'status' => StatusCollection::PARAM_STATUS_TEMPORARY,
            'token' => $token
        );

        // Save data
        $this->transactionMapper->persist($data);

        return $token;
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
