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
use Krystal\Stdlib\VirtualEntity;

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
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Payments', 'Payment:Admin:Transaction@indexAction')
                   ->addOne($transaction->getId() ? 'Edit the transaction' : 'Add new transaction');

        return $this->view->render('form', array(
            'transaction' => $transaction
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
            return 1;
        } else {
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
        return $this->createForm(new VirtualEntity);
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
