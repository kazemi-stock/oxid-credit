<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Voucher\Service;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Application\Model\VoucherSerie;

interface VoucherServiceInterface
{
    /**
     * @param float $discount
     * @return VoucherSerie
     */
    public function createVoucherSerie(float $discount): VoucherSerie;

    /**
     * @param string $userId
     * @param string $voucherSeriesId
     * @param float $discount
     * @return Voucher
     */
    public function createVoucher(string $userId, string $voucherSeriesId, float $discount): Voucher;

}
