<?php

class TestAllowsStockAlert
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

class ErgonTech_SimpleProductAlert_CanSubscribePredicateTest extends PHPUnit_Framework_TestCase
{
    public function testWhenStockIsAllowedAndProductIsAvailable()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlert(true));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $product->isAvailable()->willReturn(true);
        static::assertFalse(ErgonTech_SimpleProductAlert_CanSubscribePredicate::check($product->reveal()),
            'The predicate should return false when the product is available');
    }

    public function testWhenStockIsNotAllowed()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlert(false));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);

        static::assertFalse(ErgonTech_SimpleProductAlert_CanSubscribePredicate::check($product->reveal()),
            'The predicate should return false when stock alerts are not allowed');
    }

    public function testWhenStockIsAllowedAndProductNotAvailable()
    {
        Mage::reset();
        Mage::register('_helper/productalert', new TestAllowsStockAlert(true));
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $product->isAvailable()->willReturn(false);

        static::assertTrue(ErgonTech_SimpleProductAlert_CanSubscribePredicate::check($product->reveal()),
            'The predicate should return true when stock alerts are allowed and the product is unavailable');
    }
}
