<?php

namespace OxidEsales\ModuleTemplate\Tests\Unit\Voucher\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\Eshop\Application\Model\VoucherSerie;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use PHPUnit\Framework\TestCase;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherModelFactory;
use OxidEsales\ModuleTemplate\Voucher\Infrastructure\VoucherSerieModelFactory;

class VoucherSerieModelFactoryTest extends TestCase
{
    public function testVoucherSerieModelFactoryCreateCorrectInstanceOfVoucherSerieModel(): void
    {
        $voucherSerieModelFactory = new VoucherSerieModelFactory();
        $voucherSerie = $voucherSerieModelFactory->create(100.0);
        $this->assertInstanceOf(VoucherSerie::class, $voucherSerie);
    }

    /**
     * @throws \Exception
     */
    public function testCreateVoucherSerieCallAssignMethod()
    {
        $voucherSerieMock = $this
            ->getMockBuilder(VoucherSerie::class)
            ->setMethods(['assign'])
            ->getMock();

        $voucherSerieMock->expects($this->once())->method('assign');
        $voucherSerieMock->assign([
            'OXSHOPID' => 1,
            'OXSERIENR' => 'User Credit',
            'OXDISCOUNT' => 100.0,
            'OXDISCOUNTTYPE' => 'absolute',
        ]);

        $voucherSerieModelFactory = new VoucherSerieModelFactory();
        $voucherSerieModelFactory->create(100.0);
    }

    public function testCreateVoucherSerieSuccessfully()
    {

        $voucherSerieModelFactory = new VoucherSerieModelFactory();
        $voucherSerie = $voucherSerieModelFactory->create(100.0);

        // Assert
        $this->assertInstanceOf(VoucherSerie::class, $voucherSerie);
        $this->assertEquals(100.0, $voucherSerie->oxvoucherseries__oxdiscount->value);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testCreateVoucherSerieThrowsExceptionWhenDiscountIsEmpty()
    {
        $this->expectException(VoucherException::class);

        $voucherSerieModelFactory = new VoucherSerieModelFactory();
        $voucherSerieModelFactory->create(-0.0);
    }
}
