<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ModuleTemplate\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\VoucherSerie;

interface VoucherSerieModelFactoryInterface
{
    /**
     * Store VoucherSerie into the database
     * @param float $discount
     * @return VoucherSerie
     * @throws \Exception
     */
    public function create(float $discount): VoucherSerie;

}
