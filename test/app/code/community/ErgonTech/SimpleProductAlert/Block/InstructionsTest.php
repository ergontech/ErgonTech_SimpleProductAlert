<?php


class ErgonTech_SimpleProductAlert_Block_InstructionsTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Mage::reset();
        $store = $this->prophesize(Mage_Core_Model_Store::class);
        $store->getConfig(ErgonTech_SimpleProductAlert_Block_Instructions::XML_PATH_OOS_MODAL)
            ->willReturn('modal');

        $store->getConfig(ErgonTech_SimpleProductAlert_Block_Instructions::XML_PATH_OOS_HELPER_TEXT)
            ->willReturn('helper');

        $reflectionMage = new ReflectionClass(Mage::class);

        $app = $this->prophesize(Mage_Core_Model_App::class);
        $app->setUpdateMode(\Prophecy\Argument::type('bool'))->willReturn(null);

        $app->getStore(\Prophecy\Argument::any())->willReturn($store->reveal());

        static::setStaticPropertyValue($reflectionMage,
            '_isInstalled', true);
        static::setStaticPropertyValue($reflectionMage,
            '_app', $app->reveal());
    }

    protected static function setStaticPropertyValue(ReflectionClass $reflectedClass, $propName, $propValue)
    {
        $prop = $reflectedClass->getProperty($propName);
        $prop->setAccessible(true);
        $prop->setValue($propValue);
    }

    public function testGetOosContent()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Instructions();
        static::assertEquals('modal', $subj->getOosContent());
    }

    public function testGetOosHelperText()
    {
        $subj = new ErgonTech_SimpleProductAlert_Block_Instructions();
        static::assertEquals('helper', $subj->getOosHelperText());
    }
}
