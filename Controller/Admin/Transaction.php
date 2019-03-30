<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Payment\Collection\StatusCollection;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Date\TimeHelper;

final class Transaction extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $transaction
     * @return string
     */
    private function createForm(VirtualEntity $transaction)
    {
        $stCol = new StatusCollection();

        // Extension service
        $extensionService = $this->getModuleService('extensionService');

        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Payments', 'Payment:Admin:Transaction@indexAction')
                   ->addOne($transaction->getId() ? 'Edit the transaction' : 'Add new transaction');

        return $this->view->render('form', array(
            'transaction' => $transaction,
            'statuses' => $stCol->getAll(),
            'modules' => $extensionService->getModules(),
            'extensions' => $extensionService->getExtensions()
        ));
    }

    /**
     * Saves transaction
     * 
     * @return string
     */
    public function saveAction()
    {
        // Get raw POST data
        $input = $this->request->getPost('transaction');

        $transactionService = $this->getModuleService('transactionService');
        $transactionService->save($input);

        if ($input['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $transactionService->getLastId();
        }
    }

    /**
     * Renders add form
     * 
     * @return string
     */
    public function addAction()
    {
        $transaction = new VirtualEntity;
        $transaction->setDatetime(TimeHelper::getNow());

        return $this->createForm($transaction);
    }

    /**
     * Renders edit form
     * 
     * @param int $id Transaction id
     * @return string
     */
    public function editAction($id)
    {
        $transaction = $this->getModuleService('transactionService')->fetchById($id);

        if ($transaction) {
            return $this->createForm($transaction);
        } else {
            return false;
        }
    }

    /**
     * Renders main grid
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Payments');

        return $this->view->render('index', array(
            'transactions' => $this->getModuleService('transactionService')->fetchAll()
        ));
    }

    /**
     * Deletes a transaction by its id
     * 
     * @param int $id Transaction id
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('transactionService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }
}
