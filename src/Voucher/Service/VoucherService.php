<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Voucher\Service;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Application\Model\VoucherSerie;
use WebBakery\Credit\Voucher\Infrastructure\VoucherModelFactoryInterface;
use WebBakery\Credit\Voucher\Infrastructure\VoucherSerieModelFactoryInterface;

class VoucherService implements VoucherServiceInterface
{
    /**
     * @var VoucherModelFactoryInterface
     */
    private VoucherModelFactoryInterface $voucherModelFactory;

    /**
     * @var VoucherSerieModelFactoryInterface
     */
    private $voucherSerieModelFactory;

    public function __construct(
        VoucherModelFactoryInterface $voucherModelFactory,
        VoucherSerieModelFactoryInterface $voucherSerieModelFactory
    ) {
        $this->voucherModelFactory = $voucherModelFactory;
        $this->voucherSerieModelFactory = $voucherSerieModelFactory;
    }

    /**
     * @param float $discount
     * @return VoucherSerie
     * @throws \Exception
     */
    public function createVoucherSerie(float $discount): VoucherSerie
    {
        $voucherSerieModel = $this->voucherSerieModelFactory->create($discount);

        return $voucherSerieModel;
    }

    /**
     * @param string $userId
     * @param string $voucherSeriesId
     * @param float $discount
     * @return Voucher
     * @throws \OxidEsales\Eshop\Core\Exception\VoucherException
     */
    public function createVoucher(string $userId, string $voucherSeriesId, float $discount): Voucher
    {
        $voucherModel = $this->voucherModelFactory->create($userId, $voucherSeriesId, $discount);

        return $voucherModel;
    }

}
