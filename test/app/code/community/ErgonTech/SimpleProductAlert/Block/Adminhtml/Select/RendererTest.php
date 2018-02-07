<?php

class ErgonTech_SimpleProductAlert_Block_Adminhtml_Select_RendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * Assert that when the subject does not have a value, the attribute's default value is used instead
     */
    public function testGetElementHtmlWithoutValue()
    {
        $fakeEntityAttribute = $this->prophesize(Mage_Catalog_Model_Resource_Eav_Attribute::class);
        $fakeEntityAttribute->getDefaultValue()
            ->willReturn('something')
            ->shouldBeCalled();

        $fakeForm = $this->prophesize(UghMagicMethodsForm::class);

        $subj = new ErgonTech_SimpleProductAlert_Block_Adminhtml_Select_Renderer;
        $subj->setEntityAttribute($fakeEntityAttribute->reveal());
        $subj->setForm($fakeForm->reveal());
        $subj->getElementHtml();
    }

    /**
     * Assert that when the subject DOES have a value, the attribute's default value isn't read by the subject
     */
    public function testGetElementHtmlWithValue()
    {
        $fakeEntityAttribute = $this->prophesize(Mage_Catalog_Model_Resource_Eav_Attribute::class);
        $fakeEntityAttribute->getDefaultValue()
            ->shouldNotBeCalled();

        $fakeForm = $this->prophesize(UghMagicMethodsForm::class);

        $subj = new ErgonTech_SimpleProductAlert_Block_Adminhtml_Select_Renderer;
        $subj->setEntityAttribute($fakeEntityAttribute->reveal());
        $subj->setForm($fakeForm->reveal());
        $subj->setValue('something');
        $subj->getElementHtml();
    }
}

class UghMagicMethodsForm extends Varien_Data_Form
{
    public function getHtmlIdPrefix() { return ''; }
    public function getHtmlIdSuffix() { return ''; }
    public function getFieldNameSuffix() { return ''; }
}