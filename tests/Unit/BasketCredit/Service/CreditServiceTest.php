<?php

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Unit\BasketCredit\Service;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use PHPUnit\Framework\TestCase;
use OxidEsales\ModuleTemplate\BasketCredit\Service\CreditService;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactoryInterface;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactoryInterface;
use OxidEsales\ModuleTemplate\Voucher\Service\VoucherService;

/**
 * @covers \OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory
 */
class CreditServiceTest extends TestCase
{

    public function testApplyCreditAddVoucherToBasket()
    {
        $data = $this->dataProvider();

        $voucherModelFactoryMock = $this->createMock(VoucherModelFactoryInterface::class);
        $voucherSerieModelFactoryMock = $this->createMock(VoucherSerieModelFactoryInterface::class);
        $voucherService = new VoucherService(
            $voucherModelFactoryMock,
            $voucherSerieModelFactoryMock
        );

        $voucherService->createVoucherSerie($data['correctDiscount']);

        $voucherService->createVoucher(
            $data['userId'],
            $data['voucherSeriesId'],
            $data['correctDiscount']
        );

        $basketMock = $this->oxNew(Basket::class);
        $creditService = new CreditService($basketMock, $data['userId'], $data['correctDiscount']);

        $result = $creditService->applyCredit();

        $this->assertTrue($result);
    }

    public function testApplyCreditThrowsExceptionWhenDiscountIsIncorrect()
    {
        $this->expectException(VoucherException::class);

        $data = $this->dataProvider();

        $voucherModelFactoryMock = $this->createMock(VoucherModelFactoryInterface::class);
        $voucherSerieModelFactoryMock = $this->createMock(VoucherSerieModelFactoryInterface::class);
        $voucherService = new VoucherService(
            $voucherModelFactoryMock,
            $voucherSerieModelFactoryMock
        );

        $voucherService->createVoucherSerie($data['correctDiscount']);

        $voucherService->createVoucher(
            $data['userId'],
            $data['voucherSeriesId'],
            $data['correctDiscount']
        );

        $basketMock = $this->createMock(Basket::class);
        $creditService = new CreditService($basketMock, $data['userId'], $data['incorrectDiscount']);

        $creditService->applyCredit();

    }

    /**
     * Data provider
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            'userId' => 'test-user-id',
            'voucherSeriesId' => 'test-voucher-series-id',
            'correctDiscount' => 100.0,
            'incorrectDiscount' => -0.0
        ];
    }
    
    private function oxNew($class)
    {
        return new $class;
    }
}