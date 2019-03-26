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

interface WebExtensionInterface
{
    /**
     * Returns form action URL
     * 
     * @return string
     */
    public function getAction();

    /**
     * Returns method to be used when submitting a form
     * 
     * @return string
     */
    public function getMethod();

    /**
     * Render form fields
     * 
     * @return string
     */
    public function renderFields();
}
