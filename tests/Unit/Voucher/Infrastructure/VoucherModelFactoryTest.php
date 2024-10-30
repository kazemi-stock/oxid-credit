<?php

namespace OxidEsales\ModuleTemplate\Tests\Unit\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use PHPUnit\Framework\TestCase;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory;

class VoucherModelFactoryTest extends TestCase
{
    public function testVoucherModelFactoryCreateCorrectInstanceOfVoucherModel(): void
    {
        // Test data
        $data = $this->dataProvider();
        // Create the factory instance
        $voucherModelFactory = new VoucherModelFactory();
        $voucher = $voucherModelFactory->create($data['userId'], $data['voucherSeriesId'], $data['correctDiscount']);
        $this->assertInstanceOf(Voucher::class, $voucher);
    }

    /**
     * @throws \Exception
     */
    public function testCreateVoucherCallAssignMethod()
    {
        // Test data
        $data = $this->dataProvider();

        $voucherMock = $this
            ->getMockBuilder(Voucher::class)
            ->setMethods(['assign'])
            ->getMock();

        $voucherMock->expects($this->once())->method('assign');
        $voucherMock->assign([
            'OXUSERID' => $data['userId'],
            'OXVOUCHERNR' => $this->isType('string'),
            'OXVOUCHERSERIEID' => $data['voucherSeriesId'],
            'OXDISCOUNT' => $data['correctDiscount'],
        ]);

        // Create the factory instance and call the method
        $voucherModelFactory = new VoucherModelFactory;
        $voucherModelFactory->create($data['userId'], $data['voucherSeriesId'], $data['correctDiscount']);
    }

    public function testCreateVoucherSuccessfully()
    {

        $data = $this->dataProvider();
        // Create the factory instance and call the method
        $voucherModelFactory = new VoucherModelFactory;
        $voucher = $voucherModelFactory->create($data['userId'], $data['voucherSeriesId'], $data['correctDiscount']);

        // Assert
        $this->assertInstanceOf(Voucher::class, $voucher);
        $this->assertEquals($data['correctDiscount'], $voucher->oxvouchers__oxdiscount->value);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testCreateVoucherThrowsExceptionWhenDiscountIsEmpty()
    {
        $this->expectException(VoucherException::class);
        $data = $this->dataProvider();

        $voucherModelFactory = new VoucherModelFactory();
        $voucherModelFactory->create($data['userId'], $data['voucherSeriesId'], $data['incorrectDiscount']);
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
