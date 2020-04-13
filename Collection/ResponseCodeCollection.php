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

final class ResponseCodeCollection extends ArrayCollection
{
    /* Main response statuses */
    const RESPONSE_SUCCESS = 0;
    const RESPONSE_CANCEL = 1;
    const RESPONSE_DUPLICATE = 2;
}
