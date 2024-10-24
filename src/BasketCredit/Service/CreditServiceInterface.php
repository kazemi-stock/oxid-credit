<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\BasketCredit\Service;

interface CreditServiceInterface
{
    /**
     * @return bool
     */
    public function applyCredit(): bool;
}