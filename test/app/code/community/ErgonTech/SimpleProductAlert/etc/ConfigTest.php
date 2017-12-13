<?php

class ErgonTech_SimpleProductAlerts_etc_ConfigTest extends \MageTest_PHPUnit_Framework_TestCase
{
    const MODULE_NAME = 'ErgonTech_SimpleProductAlert';
    use \ErgonTech\MageTest\LayoutHelpers;
    /** @var  Mage_Core_Model_Config_Base */
    protected $config;

    public function setUp()
    {
        $this->config = new \Mage_Core_Model_Config_Base(__DIR__ . '/../../../../../../../app/code/community/ErgonTech/SimpleProductAlert/etc/config.xml');
    }

    public function testModuleDeclaration()
    {
        $module = $this->config->getNode('modules/' . self::MODULE_NAME);

        static::assertEquals(self::MODULE_NAME, $module->getName());

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

    public function testProductalertsHelperRewrite()
    {
        $helper = $this->config->getNode('global/helpers/productalert');
        static::assertXpathHasResults($helper, 'rewrite/data[.="ErgonTech_SimpleProductAlert_Helper_Productalert_Data"]');
    }

    public function testBlockDeclaration()
    {
        $block = $this->config->getNode('global/blocks/simpleproductalert');

        static::assertXpathHasResults($block, 'class[.="ErgonTech_SimpleProductAlert_Block"]');
    }

    public function testModelDeclaration()
    {
        $block = $this->config->getNode('global/models/simpleproductalert');

        static::assertXpathHasResults($block, 'class[.="ErgonTech_SimpleProductAlert_Model"]');
    }

    public function testSetupDeclaration()
    {
        $moduleName = static::MODULE_NAME;
        $resource = $this->config->getNode('global/resources/simpleproductalert_setup');
        static::assertXpathHasResults($resource, 'setup/class[.="Mage_Catalog_Model_Resource_Setup"]');
        static::assertXpathHasResults($resource, "setup/module[.=\"{$moduleName}\"]");
        static::assertXpathHasResults($resource, 'connection/use[.="default_setup"]');

    }
}