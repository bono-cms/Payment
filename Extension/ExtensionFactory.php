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

use RuntimeException;

/**
 * General factory class that calls extension factories
 */
final class ExtensionFactory
{
    /**
     * Builds extension instance
     * 
     * @param string $extension Extension name
     * @param mixed $price The price
     * @param int $orderId Order ID
     * @param string $back Back URL on success
     * @param array $options Extension options
     * @throws \RuntimeException If invald extensio name provided
     * @return \Payment\Extension\WebFormExtensionInterface
     */
    public static function build($extension, $price, $orderId, $back, array $options = array())
    {
        // Extension class factory to be verified for existence
        $className = sprintf('\Payment\Extension\%s\%sFactory', $extension, $extension);

        if (!class_exists($className)) {
            throw new RuntimeException(
                sprintf('Either payment extension %s does not exist or it has inconvenient internal structure', $extension)
            );
        } else {
            $class = new $className();
            return $class->build($price, $orderId, $back, $options);
        }
    }
}
