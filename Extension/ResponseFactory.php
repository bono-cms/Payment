<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Extension;

use Krystal\InstanceManager\ServiceLocatorInterface;
use RuntimeException;

final class ResponseFactory
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
     * Builds response instance
     * 
     * @param string $extension
     * @return \Payment\Extension\ResponseHandlerInterface
     */
    public function build($extension)
    {
        $className = sprintf('\Payment\Extension\%s\ResponseHandler', $extension);

        if (!class_exists($className)) {
            throw new RuntimeException(
                sprintf('Either payment extension %s does not exist or it has inconvenient internal structure', $extension)
            );
        } else {
            return new $className($this->sl);
        }
    }
}
