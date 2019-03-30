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
use Krystal\Filesystem\FileManager;

final class ExtensionService
{
    /**
     * Module manager instance
     * 
     * @var \Krystal\Application\Module\ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * Supported modules that might accept payments
     * 
     * @var array
     */
    private $constraints = array();

    /**
     * State initialization
     * 
     * @param \Krystal\Application\Module\ModuleManagerInterface $moduleManager
     * @param array $constraints Supported modules that might accept payments
     * @return void
     */
    public function __construct(ModuleManagerInterface $moduleManager, array $constraints)
    {
        $this->moduleManager = $moduleManager;
        $this->constraints = $constraints;
    }

    /**
     * Returns currently loaded modules that support payments
     * 
     * @return array
     */
    public function getModules()
    {
        // Currently loaded modules
        $modules = $this->moduleManager->getLoadedModuleNames();
        $supported = array(); // Modules that support payments

        foreach ($modules as $module) {
            if (in_array($module, $this->constraints)) {
                $supported[] = $module;
            }
        }

        return ArrayUtils::valuefy($supported);
    }

    /**
     * Returns currently loaded payment extensions
     * 
     * @return array
     */
    public function getExtensions()
    {
        // Path to extensions directory
        $dir = dirname(__DIR__) . '\\Extension';

        $extensions = FileManager::getFirstLevelDirs($dir);

        return ArrayUtils::valuefy($extensions);
    }
}
