<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ModuleTemplate\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Core\Exception\VoucherException;

interface VoucherModelFactoryInterface
{
    /**
     * Store and return Voucher
     *
     * @param string $userId
     * @param string $voucherSeriesId
     * @param float $discount
     * @return Voucher
     * @throws VoucherException
     */
    public function create(string $userId, string $voucherSeriesId, float $discount): Voucher;

}
