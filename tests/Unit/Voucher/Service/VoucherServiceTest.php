<?php

namespace OxidEsales\ModuleTemplate\Tests\Unit\Voucher\Service;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Application\Model\VoucherSerie;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactoryInterface;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactoryInterface;
use OxidEsales\ModuleTemplate\Voucher\Service\VoucherService;

class VoucherServiceTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function testServiceCreateVoucherSuccessfully()
    {
        $data = $this->dataProvider();

        $voucherModelFactoryMock = $this->getMockBuilder(VoucherModelFactoryInterface::class)
            ->onlyMethods(['create'])->getMock();
        $voucherSerieModelFactoryMock = $this->getMockBuilder(VoucherSerieModelFactoryInterface::class)
            ->onlyMethods(['create'])->getMock();
        $voucherMock = $this->getMockBuilder(Voucher::class)
            ->onlyMethods(['assign', 'save'])->getMock();

        $voucherMock->oxvouchers__oxdiscount = (object) ['value' => $data['correctDiscount']];

        $voucherModelFactoryMock->method('create')
            ->with($data['userId'], $data['voucherSeriesId'], $data['correctDiscount'])
            ->willReturn($voucherMock);

        // Create the factory instance and call the method
        $voucherService = new VoucherService($voucherModelFactoryMock, $voucherSerieModelFactoryMock);
        $voucher = $voucherService->createVoucher($data['userId'], $data['voucherSeriesId'], $data['correctDiscount']);
        // Assert
        $this->assertInstanceOf(Voucher::class, $voucher);
        $this->assertEquals($data['correctDiscount'], $voucher->oxvouchers__oxdiscount->value);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testServiceCreateVoucherThrowsExceptionWhenDiscountIsEmpty()
    {
        $this->expectException(VoucherException::class);
        $data = $this->dataProvider();

        $voucherModelFactory = new VoucherModelFactory;
        $voucherSerieModelFactory = new VoucherSerieModelFactory;
        $voucherService = new VoucherService($voucherModelFactory, $voucherSerieModelFactory);
        $voucherService->createVoucher($data['userId'], $data['voucherSeriesId'], $data['incorrectDiscount']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function testServiceCreateVoucherSerieSuccessfully()
    {
        $data = $this->dataProvider();
        $voucherModelFactoryMock = $this->getMockBuilder(VoucherModelFactoryInterface::class)
            ->onlyMethods(['create'])->getMock();
        $voucherSerieModelFactoryMock = $this->getMockBuilder(VoucherSerieModelFactoryInterface::class)
            ->onlyMethods(['create'])->getMock();

        $voucherSerieMock = $this->getMockBuilder(VoucherSerie::class)
            ->onlyMethods(['assign', 'save'])->getMock();

        $voucherSerieMock->oxvoucherseries__oxdiscount = (object) ['value' => $data['correctDiscount']];

        $voucherSerieModelFactoryMock->method('create')
            ->with($data['correctDiscount'])
            ->willReturn($voucherSerieMock);

        $voucherService = new VoucherService($voucherModelFactoryMock, $voucherSerieModelFactoryMock);
        $voucherSerie = $voucherService->createVoucherSerie($data['correctDiscount']);
        // Assert
        $this->assertInstanceOf(VoucherSerie::class, $voucherSerie);
        $this->assertEquals($data['correctDiscount'], $voucherSerie->oxvoucherseries__oxdiscount->value);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testServiceCreateVoucherSerieThrowsExceptionWhenDiscountIsEmpty()
    {
        $this->expectException(VoucherException::class);
        $data = $this->dataProvider();

        $voucherModelFactory = new VoucherModelFactory();
        $voucherSerieModelFactory = new VoucherSerieModelFactory();
        $voucherService = new VoucherService($voucherModelFactory, $voucherSerieModelFactory);
        $voucherService->createVoucherSerie($data['incorrectDiscount']);
    }

    public function dataProvider()
    {
        return [
            'userId' => 'test-user-id',
            'voucherSeriesId' => 'test-voucher-series-id',
            'correctDiscount' => 100.0,
            'incorrectDiscount' => -0.0
        ];
    }
}
