<?php

use ErgonTech_SimpleProductAlert_Block_InstructionsTest as InstructionsTest;

class ErgonTech_SimpleProductAlert_Block_InstructionsTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Mage::reset();
        Mage::app();
        $store = $this->prophesize(Mage_Core_Model_Store::class);
        $store->getConfig(ErgonTech_SimpleProductAlert_Block_Instructions::XML_PATH_OOS_MODAL)
            ->willReturn(1);

        $store->getConfig(ErgonTech_SimpleProductAlert_Block_Instructions::XML_PATH_OOS_HELPER_TEXT)
            ->willReturn('helper');

        $reflectionMage = new ReflectionClass(Mage::class);

        $app = $this->prophesize(Mage_Core_Model_App::class);
        $app->setUpdateMode(\Prophecy\Argument::type('bool'))->willReturn(null);

        $app->getStore(\Prophecy\Argument::any())->willReturn($store->reveal());

        $app->dispatchEvent(\Prophecy\Argument::cetera())->willReturn($app->reveal());

        static::setStaticPropertyValue($reflectionMage,
            '_isInstalled', true);
        static::setStaticPropertyValue($reflectionMage,
            '_app', $app->reveal());
    }

    protected function tearDown()
    {
        Mage::reset();
    }

    protected static function setStaticPropertyValue(ReflectionClass $reflectedClass, $propName, $propValue)
    {
        $prop = $reflectedClass->getProperty($propName);
        $prop->setAccessible(true);
        $prop->setValue($propValue);
    }

    public function testGetOosContent()
    {
        $cmsBlockResource = $this->prophesize(Mage_Cms_Model_Resource_Block::class);
        $cmsBlockResource
            ->load(\Prophecy\Argument::type(Mage_Cms_Model_Block::class), \Prophecy\Argument::cetera())
            ->will(function ($args) {
                list($block, $id) = $args;
                InstructionsTest::assertEquals(1, $id);
                $block->setData([
                    'block_id' => $id,
                    'content' => 'modal'
                ]);
            });

        Mage::register('_resource_singleton/cms/block', $cmsBlockResource->reveal());

        $subj = new ErgonTech_SimpleProductAlert_Block_Instructions();
        static::assertEquals('modal', $subj->getOosContent());
    }

    public function testGetOosHelperText()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Instructions();
        static::assertEquals('helper', $subj->getOosHelperText());
    }
}
