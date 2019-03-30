<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Extension\Prime4G;

use Payment\Extension\ResponseHandlerInterface;
use Krystal\InstanceManager\ServiceLocatorInterface;

final class ResponseHandler implements ResponseHandlerInterface
{
    /**
     * Service locator instance
     * 
     * @var \Krystal\InstanceManager\ServiceLocatorInterface
     */
    private $sl;

    /**
     * State initialization
     * 
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @return void
     */
    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
    }

    /**
     * Checks whether request is canceled
     * 
     * @return boolean
     */
    public function canceled()
    {
        $request = $this->sl->get('request');

        $code = $request->getPost('ResponseCode');

        // Canceled request or could not accept money
        if ($code != 1) {
            return true;
        }

        // Not failed
        return false;
    }

    /**
     * Checks whether request is finished
     * 
     * @return boolean
     */
    public function successful()
    {
        return !$this->canceled();
    }
}
