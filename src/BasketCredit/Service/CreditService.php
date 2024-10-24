<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\BasketCredit\Service;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Service\VoucherService;

class CreditService implements CreditServiceInterface
{
    /** @var Basket  */
    private Basket $oBasket;
    private string $userId;
    private float $amount;


    /**
     * injection dependencies
     * @param Basket $oBasket
     * @param string $userId
     * @param float $amount
     */
    public function __construct(Basket $oBasket, string $userId, float $amount)
    {
        $this->oBasket = $oBasket;
        $this->userId = $userId;
        $this->amount = $amount;
    }

    /**
     * For add credit to basket
     * @return bool
     * @throws \Exception
     */
    public function applyCredit(): bool
    {
        if ($this->userId) {
            $voucherModelFactory = new VoucherModelFactory();
            $voucherSerieModelFactory = new VoucherSerieModelFactory();

            $voucherService = new VoucherService(
                $voucherModelFactory,
                $voucherSerieModelFactory
            );

            $voucherSerie = $voucherService->createVoucherSerie($this->amount);

            $voucher = $voucherService->createVoucher(
                $this->userId,
                $voucherSerie->oxvoucherseries__oxid->value,
                $this->amount
            );

            $this->oBasket->addVoucher($voucher->oxvouchers__oxvouchernr->value);

            if (empty($this->oBasket->getVouchers())) {
                $exception = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
                $exception->setMessage('ERROR_MESSAGE_VOUCHER_NOVOUCHER');
                $exception->setVoucherNr($voucher->oxvouchers__oxvouchernr->value);
                throw $exception;
            }else {
                return true;
            }
        }
        return false;
    }
}
