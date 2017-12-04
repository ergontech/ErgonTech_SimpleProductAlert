<?php

class ErgonTech_SimpleProductAlert_Block_Configurable_Product_ViewTest extends PHPUnit_Framework_TestCase
{
    public function testItPreparesAllChildProducts()
    {
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $configurable = $this->prophesize(Mage_Catalog_Model_Product_Type_Configurable::class);

        $product->getTypeInstance()->willReturn($configurable->reveal());
        $product->getId()->willReturn(1);
        Mage::register('current_product', $product->reveal());

        $origBlock = $this->prophesize(Mage_ProductAlert_Block_Product_View::class);

        $firstProduct = $this->prophesize(Mage_Catalog_Model_Product::class);
        $secondProduct = $this->prophesize(Mage_Catalog_Model_Product::class);

        $configurable->getUsedProducts()->willReturn([
            $firstProduct->reveal(),
            $secondProduct->reveal(),
        ])->shouldBeCalled();

        $configurable->getConfigurableAttributes($product)
            ->shouldBeCalled();

        $layout = $this->prophesize(Mage_Core_Model_Layout::class);

        $firstProductView = $this->prophesize(Mage_ProductAlert_Block_Product_View::class);
        $secondProductView = $this->prophesize(Mage_ProductAlert_Block_Product_View::class);
        $layout->createBlock('productalert/product_view', 'simplechild_0')
            ->shouldBeCalled()
            ->willReturn($firstProductView);
        $layout->createBlock('productalert/product_view', 'simplechild_1')
            ->shouldBeCalled()
            ->willReturn($secondProductView);

        $helper = $this->prophesize(ErgonTech_SimpleProductAlert_Helper_Data::class);

        $helper->getStockPredicate()
            ->willReturn(function () { return true ;});

        $helper->generateConfigurableAttributesAttrs(\Prophecy\Argument::any(), \Prophecy\Argument::any())
            ->willReturn('');

        Mage::register('_helper/simpleproductalert', $helper->reveal());

        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        $subj->setLayout($layout->reveal());
        $subj->setChild('productalert_stock', $origBlock->reveal());
        $subj->prepareStockAlertData();

        static::assertCount(2, $subj->getChild());
    }

    public function testToHtml()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        $block = new MockBlockClass('hello');
        $block2 = new MockBlockClass('goodbye');

        $subj->setChild('first', $block);
        $subj->setChild('second', $block2);

        static::assertEquals('hellogoodbye', $subj->toHtml());
    }
}

class MockBlockClass
{
    private $value;

    public function __call($name, $arguments)
    {
        // just a shortcut
    }

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toHtml()
    {
        return $this->value;
    }
}
