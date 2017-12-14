<?php

namespace test\ErgonTech;

use ErgonTech\MageTest\LayoutHelpers;

class LayoutTest extends \MageTest_PHPUnit_Framework_TestCase
{
    use LayoutHelpers;

    /**
     * @var \Varien_Simplexml_Config
     */
    private $layout;

    public function setUp()
    {
        $filename = __DIR__ . '/../../../../../../app/design/frontend/base/default/layout/ergontech_simpleproductalert.xml';
        $this->layout = new \Varien_Simplexml_Config($filename);
    }

    public function testConfigurableHandleGetsNewBlock()
    {
        $node = $this->layout->getNode();
        static::assertXpathHasResults($node, 'PRODUCT_TYPE_configurable');
        static::assertXpathHasResults($node, 'PRODUCT_TYPE_configurable/reference[@name="product.info.addtocart"]/block[@type="simpleproductalert/configurable_product_view"]');
    }

    public function testConfigurableHandleMovesDefaultBlock()
    {
        $node = $this->layout->getNode();
        static::assertXpathHasResults($node, 'PRODUCT_TYPE_configurable/remove[@name="productalert.stock"]');
        $xpath = 'PRODUCT_TYPE_configurable/reference[@name="product.info.addtocart"]/block[@name="simpleproductalert.stock"]/block[@name="productalert_stock"]';
        static::assertXpathHasResults($node, $xpath);

        /** @var \Varien_Simplexml_Element $node */
        $node = current($node->xpath($xpath));
        static::assertEquals('productalert/product_view', $node->getAttribute('type'));
        static::assertEquals('simpleproductalert/product/view/button.phtml', $node->getAttribute('template'));
    }

    public function testSignupLabelIsSet()
    {
        $block = $this->layout->getXpath('PRODUCT_TYPE_configurable/reference[@name="product.info.addtocart"]/block[@as="simpleproductalert_stock"]');
        static::assertNotEmpty($block);

        static::assertXpathHasResults(current($block), 'action[@method="setSignupLabel"][@translate="value"]/value[.="Notify Me When Back in Stock"]');
    }

    public function testCoreBlockIsAdded()
    {
        $result = $this->layout->getXpath('PRODUCT_TYPE_configurable/reference[@name="product.info.addtocart"]/block[@as="simpleproductalert_stock"]');
        static::assertNotEmpty($result);
        $block = current($result);
        static::assertXpathHasResults($block, 'block[@type="productalert/product_view"][@as="productalert_stock"]');
    }

    public function testInstructionBlockIsAddedToStockAlertBlock()
    {
        $configurableHandle = $this->layout->getXpath('PRODUCT_TYPE_configurable');
        static::assertCount(1, $configurableHandle);

        $alertBlock = 'block[@name="simpleproductalert.stock"][@type="simpleproductalert/configurable_product_view"]';
        $instructionBlock = 'block[@type="simpleproductalert/instructions"][@as="instructions"]';
        static::assertXpathHasResults(current($configurableHandle), "//{$alertBlock}/$instructionBlock");
    }

}