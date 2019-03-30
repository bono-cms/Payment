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

use Payment\Extension\WebFormExtensionInterface;

final class WebForm implements WebFormExtensionInterface
{
    /**
     * Default options
     * 
     * @var array
     */
    private $options = array(
        'Version' => '1.0.0',
        'SignatureMethod' => 'SHA1',
        'PurchaseCurrency' => '840',
        'PurchaseCurrencyExponent' => '2'
    );

    /**
     * State initialization
     * 
     * @param array $options
     * @return void
     */
    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Returns form action
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->options['action'];
    }

    /**
     * Returns form request method to be used when submitting
     * 
     * @return string
     */
    public function getMethod()
    {
        return 'POST';
    }

    /**
     * Normalizes the price
     * 
     * @param string $value
     * @return integer
     */
    public static function normalizePrice($value)
    {
        // Format the number
        $value = number_format((float) $value, 2);

        // Remove dots
        $value = str_replace(array('.', ','), '', $value);

        $length = strlen($value);

        if ($length <= 5) {
            return str_repeat(0, 12 - $length) . $value;
        } else {
            return str_repeat(0, 13 - $length) . $value;
        }
    }

    /**
     * Render fields
     * 
     * @return string
     */
    public function renderFields()
    {
        $fragment = null;

        foreach ($this->createFields() as $name => $value) {
            // Don't render password
            if ($name === 'Password') {
                continue;
            }

            $fragment .= sprintf('<input type="hidden" name="%s" value="%s">', $name, $value) . PHP_EOL;
        }

        return $fragment;
    }

    /**
     * Create fields to be rendered
     * 
     * @return array
     */
    private function createFields()
    {
        return array_merge($this->options, array(
            // Dynamic fields
            'Signature' => $this->createSignature()
        ));
    }

    /**
     * Creates the signature
     * 
     * @return string
     */
    private function createSignature()
    {
        // Secret string
        $secret = $this->options['Password'] . 
                  $this->options['MerID'] . 
                  $this->options['AcqID'] . 
                  $this->options['OrderID'] . 
                  $this->options['PurchaseAmt'] . 
                  $this->options['PurchaseCurrency'];

        return base64_encode(sha1($secret, true));
    }
}
