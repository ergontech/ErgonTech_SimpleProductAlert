<?php

class ErgonTech_SimpleProductAlerts_etc_ConfigTest extends \MageTest_PHPUnit_Framework_TestCase
{

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
}