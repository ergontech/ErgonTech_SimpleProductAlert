<?php
/**
 * Date: 12/13/17
 * Time: 10:22
 */


class ErgonTech_SimpleProductAlert_Helper_Productalert_DataTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Mage::reset();
        $store = $this->prophesize(Mage_Core_Model_Store::class);
        $store->getConfig(Mage_ProductAlert_Model_Observer::XML_PATH_STOCK_ALLOW)
            ->willReturn(1, 0);

        $reflectionMage = new ReflectionClass(Mage::class);

        $app = $this->prophesize(Mage_Core_Model_App::class);
        $app->setUpdateMode(\Prophecy\Argument::type('bool'))->willReturn(null);

        $app->getStore(\Prophecy\Argument::any())->willReturn($store->reveal());

        static::setStaticPropertyValue($reflectionMage,
            '_isInstalled', true);
        static::setStaticPropertyValue($reflectionMage,
            '_app', $app->reveal());
    }

    public function testIsStockAlertAllowed()
    {
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $product->getData('productalert_stock')->willReturn(1, 0, 2, 2);
        $subj = new ErgonTech_SimpleProductAlert_Helper_Productalert_Data();
        $subj->setProduct($product->reveal());

        static::assertTrue($subj->isStockAlertAllowed());
        static::assertFalse($subj->isStockAlertAllowed());
        static::assertTrue($subj->isStockAlertAllowed());
        static::assertFalse($subj->isStockAlertAllowed());
    }

    protected static function setStaticPropertyValue(ReflectionClass $reflectedClass, $propName, $propValue)
    {
        $prop = $reflectedClass->getProperty($propName);
        $prop->setAccessible(true);
        $prop->setValue($propValue);
    }
}
