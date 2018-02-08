<?php

class TestAllowsStockAlertSend
{
    private $allowed;

    public function __construct($allowed)
    {
        $this->allowed = $allowed;
    }

    public function isStockAlertAllowed()
    {
        return $this->allowed;
    }
}

class ErgonTech_SimpleProductAlert_CanNotifyPredicateTest extends PHPUnit_Framework_TestCase
{
    public function testNotifyAllowed()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlertSend(true));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $product->isAvailable()->willReturn(true);
        static::assertTrue(ErgonTech_SimpleProductAlert_CanNotifyPredicate::check($product->reveal()),
            'The predicate should return true when the product is available and alerts are allowed');
    }

    public function testNotAllowed()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlertSend(false));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);

        static::assertFalse(ErgonTech_SimpleProductAlert_CanNotifyPredicate::check($product->reveal()),
            'The predicate should return false when stock alerts are not allowed');
    }

    public function testWhenStockIsAllowedAndProductNotAvailable()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlertSend(true));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $product->isAvailable()->willReturn(false);

        static::assertFalse(ErgonTech_SimpleProductAlert_CanNotifyPredicate::check($product->reveal()),
            'The predicate should return false when stock alerts are allowed and the product is unavailable');
    }
}
