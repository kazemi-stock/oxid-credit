<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;

class VoucherModelFactory implements VoucherModelFactoryInterface
{
    /**
     * @param string $userId
     * @param string $voucherSeriesId
     * @param float $discount
     * @return Voucher
     * @throws \Exception
     */
    public function create(string $userId, string $voucherSeriesId, float $discount): Voucher
    {
        $voucherNr = random_int(1111, 9999);
        $voucher = oxNew(Voucher::class);
        $voucher->assign([
            'OXUSERID' => $userId,
            'OXVOUCHERNR' => $voucherNr,
            'OXVOUCHERSERIEID' => $voucherSeriesId,
            'OXDISCOUNT' => $discount,
        ]);
        $voucher->save();

        if (empty($voucher->oxvouchers__oxdiscount->value)) {
            $exception = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
            $exception->setMessage('ERROR_MESSAGE_VOUCHER_NOVOUCHER');
            $exception->setVoucherNr($voucher->oxvouchers__oxvouchernr->value);
            throw $exception;
        }

        return $voucher;
    }
}
