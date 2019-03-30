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

use Krystal\Application\Module\ModuleManagerInterface;
use Krystal\Stdlib\ArrayUtils;

final class ExtensionService
{
    /**
     * Module manager instance
     * 
     * @var \Krystal\Application\Module\ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * State initialization
     * 
     * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
     * @return void
     */
    public function __construct(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Returns currently loaded modules that support payments
     * 
     * @return array
     */
    public function getModules()
    {
        return ArrayUtils::valuefy($this->moduleManager->getLoadedModuleNames());
    }
}
