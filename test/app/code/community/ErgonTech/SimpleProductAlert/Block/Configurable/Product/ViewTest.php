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

        $notifyLinks = $this->prophesize(Mage_Core_Block_Text_List::class);

        $notifyLinks->getIsAnonymous()
            ->willReturn(false);

        $notifyLinks->setParentBlock(\Prophecy\Argument::type(ErgonTech_SimpleProductAlert_Block_Configurable_Product_View::class))
            ->willReturn(null);

        $notifyLinks->setBlockAlias(\Prophecy\Argument::type('string'))
            ->willReturn(null);

        $notifyLinks
            ->setChild('mychild0', \Prophecy\Argument::type(Mage_ProductAlert_Block_Product_View::class))
            ->shouldBeCalled();

        $notifyLinks
            ->setChild('mychild1', \Prophecy\Argument::type(Mage_ProductAlert_Block_Product_View::class))
            ->shouldBeCalled();

        $layout->createBlock('core/text_list', 'notify_links')
            ->shouldBeCalled()
            ->willReturn($notifyLinks->reveal());

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
    }

    public function testToHtml()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        $block = new MockBlockClass('hello');

        $subj->setChild('notify_links', $block);

        static::assertEquals('hello', $subj->toHtml());
    }

    public function testAfterToHtmlWithoutLinks()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        $subj->setChild('instructions', new MockBlockClass(' world!'));
        static::assertEquals('', $subj->toHtml());
    }

    public function testAfterToHtmlWithLinks()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        $subj->setChild('instructions', new MockBlockClass(' world!'));
        $subj->setChild('notify_links', new MockBlockClass('hello'));
        static::assertEquals('hello world!', $subj->toHtml());
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
