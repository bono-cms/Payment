<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Collection;

use Krystal\Stdlib\ArrayCollection;

final class StatusCollection extends ArrayCollection
{
    const PARAM_STATUS_TEMPORARY = -1;
    const PARAM_STATUS_COMPLETE = 1;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::PARAM_STATUS_TEMPORARY => 'Temporary',
        self::PARAM_STATUS_COMPLETE => 'Complete'
    );
}
