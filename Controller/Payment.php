<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Controller;

use Site\Controller\AbstractController;
use Payment\Collection\StatusCollection;
use Payment\Collection\ResponseCodeCollection;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;

/**
 * Main payment controller, that handles all transactions
 */
final class Payment extends AbstractController
{
    use PaymentTrait;

    /**
     * {@inheritDoc}
     */
    protected function bootstrap($action)
    {
        // Disabled CSRF for gateway action
        if ($action === 'responseAction') {
            $this->enableCsrf = false;
        }

        parent::bootstrap($action);
    }

    /**
     * Handle success or failure after payment gets done
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function responseAction($token)
    {
        // Find transaction row by its token
        $transaction = $this->getModuleService('transactionService')->fetchByToken($token);
        $response = $this->createReponse($transaction['extension']);

        if ($response->canceled()) {
            return $this->renderResponse(ResponseCodeCollection::RESPONSE_CANCEL);
        } else {
            // Now confirm payment by token, since its successful
            $this->getModuleService('transactionService')->confirmPayment($token);
            return $this->renderResponse(ResponseCodeCollection::RESPONSE_SUCCESS);
        }
    }

    /**
     * Invokes gateway by its associated token
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function gatewayAction($token)
    {
        // Find transaction row by its token
        $transaction = $this->getModuleService('transactionService')->fetchByToken($token);

        if ($transaction) {
            // Don't allow processing finished transaction
            if ($transaction['status'] == StatusCollection::PARAM_STATUS_COMPLETE) {
                return $this->renderResponse(ResponseCodeCollection::RESPONSE_DUPLICATE);
            }

            return $this->renderGateway('Payment:Payment@responseAction', $transaction);
        } else {
            // Invalid token
            return false;
        }
    }

    /**
     * Creates new transaction
     * 
     * @return string
     */
    public function newAction()
    {
        if ($this->request->isGet()) {
            $entity = new VirtualEntity();

            // Fill amount and product if provided
            $entity['product'] = $this->request->getQuery('product');
            $entity['amount'] = $this->request->getQuery('amount', false);
            $entity['currency'] = 'USD';

            $this->switchToPaymentView();

            return $this->view->render('form', array(
                'entity' => $entity,
                'extensions' => $this->getModuleService('extensionService')->getExtensions(),
                'modules' => $this->getModuleService('extensionService')->getModules(),
                'title' => 'New payment'
            ));

        } else {
            $data = $this->request->getPost();

            // Build form validator
            $formValidator = $this->createValidator(array(
                'input' => array(
                    'source' => $data,
                    'definition' => array(
                        'payer' => new Pattern\Name(),
                        'email' => new Pattern\Email()
                    )
                )
            ));

            if ($formValidator->isValid()) {
                // Add now and get last token
                $token = $this->getModuleService('transactionService')->add(
                    $data['email'],
                    $data['payer'],
                    $data['amount'],
                    $data['currency'],
                    $data['module'],
                    $data['extension']
                );

                // If amount not provided, then update
                if (!isset($data['amount'])) {
                    $this->flashBag->set('success', 'Thanks! Your invoice has been sent');
                    return '1';
                } else {
                    // Otherwise redirect to payment page
                    return $this->json(array(
                        'backUrl' => $this->request->getBaseUrl() . $this->createUrl('Payment:Payment@gatewayAction', array($token))
                    ));
                }

            } else {
                return $formValidator->getErrors();
            }
        }
    }
}
