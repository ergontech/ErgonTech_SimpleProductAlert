<?php

class ErgonTech_SimpleProductAlert_Block_Configurable_Product_ViewTest extends PHPUnit_Framework_TestCase
{
    public function testItPreparesAllChildProducts()
    {
        $product = $this->prophesize(Mage_Catalog_Model_Product::class);
        $configurable = $this->prophesize(Mage_Catalog_Model_Product_Type_Configurable::class);

        $product->getTypeInstance()->willReturn($configurable->reveal());
        $product->getId()->willReturn(1);

        $origBlock = $this->prophesize(Mage_ProductAlert_Block_Product_View::class);

        $configurable->getUsedProducts()->willReturn([
            $this->prophesize(Mage_Catalog_Model_Product::class)->reveal(),
            $this->prophesize(Mage_Catalog_Model_Product::class)->reveal(),
            $this->prophesize(Mage_Catalog_Model_Product::class)->reveal(),
            $this->prophesize(Mage_Catalog_Model_Product::class)->reveal(),
        ])->shouldBeCalled();

        $layout = $this->prophesize(Mage_Core_Model_Layout::class);

        $subj = new ErgonTech_SimpleProductAlert_Block_Configurable_Product_View();
        Mage::register('current_product', $product->reveal());

        $subj->setLayout($layout->reveal());
        $subj->setChild('productalert_stock', $origBlock->reveal());
        $subj->prepareStockAlertData();
    }
}
