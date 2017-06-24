<?php

class ErgonTech_SimpleProductAlerts_etc_ConfigTest extends \MageTest_PHPUnit_Framework_TestCase
{
    use \ErgonTech\MageTest\LayoutHelpers;
    /** @var  Mage_Core_Model_Config_Base */
    protected $config;

    public function setUp()
    {
        $this->config = new \Mage_Core_Model_Config_Base(__DIR__ . '/../../../../../../../app/code/community/ErgonTech/SimpleProductAlert/etc/config.xml');
    }

    public function testModuleDeclaration()
    {
        $module = $this->config->getNode('modules/ErgonTech_SimpleProductAlert');

        static::assertEquals('ErgonTech_SimpleProductAlert', $module->getName());

        static::assertTrue(version_compare($module->version, '0.0.0', '>='));
    }

    public function testStockCheckCallableWithStaticClass()
    {
        $callable = $this->config->getNode('simpleproductalert/stock_predicate');

        static::assertXpathHasResults($callable, 'class[.="ErgonTech_SimpleProductAlert_StockPredicate"]');
        static::assertXpathHasResults($callable, 'function[.="check"]');
    }

    public function testHelperDeclaration()
    {
        $helper = $this->config->getNode('global/helpers/simpleproductalert');

        static::assertXpathHasResults($helper, 'class[.="ErgonTech_SimpleProductAlert_Helper"]');
    }

    public function testBlockDeclaration()
    {
        $block = $this->config->getNode('global/blocks/simpleproductalert');

        static::assertXpathHasResults($block, 'class[.="ErgonTech_SimpleProductAlert_Block"]');
    }
}