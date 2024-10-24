<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\VoucherSerie;

class VoucherSerieModelFactory implements VoucherSerieModelFactoryInterface
{
    /**
     * Store and return VoucherSeries
     *
     * @return VoucherSerie
     * @throws \Exception
     */
    public function create(float $discount): VoucherSerie
    {
        $voucherSeries = oxNew(VoucherSerie::class);
        $voucherSeries->assign([
            'OXSHOPID' => 1,
            'OXSERIENR' => 'User Credit',
            'OXDISCOUNT' => $discount,
            'OXDISCOUNTTYPE' => 'absolute',
        ]);
        $voucherSeries->save();

        if (empty($voucherSeries->oxvoucherseries__oxdiscount->value)) {
            $exception = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
            $exception->setMessage('ERROR_MESSAGE_VOUCHER_NOVOUCHER');
            $exception->setVoucherNr($voucherSeries->oxvoucherseries__oxserienr->value);
            throw $exception;
        }

        return $voucherSeries;
    }
}
