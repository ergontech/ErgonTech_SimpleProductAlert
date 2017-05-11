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
        static::assertXpathHasResults($node, 'PRODUCT_TYPE_configurable/reference[@name="alert.urls"]/block[@type="simpleproductalert/configurable_product_view"]');
    }

    public function testConfigurableHandleMovesDefaultBlock()
    {
        $node = $this->layout->getNode();
        static::assertXpathHasResults($node, 'PRODUCT_TYPE_configurable/remove[@name="productalert.stock"]');
        $xpath = 'PRODUCT_TYPE_configurable/reference[@name="alert.urls"]/block[@name="simpleproductalert.stock"]/block[@name="productalert_stock"]';
        static::assertXpathHasResults($node, $xpath);

        /** @var \Varien_Simplexml_Element $node */
        $node = current($node->xpath($xpath));
        static::assertEquals('productalert/product_view', $node->getAttribute('type'));
        static::assertEquals('productalert/product/view.phtml', $node->getAttribute('template'));
    }

}