<?php

class ErgonTech_SimpleProductAlert_Block_Adminhtml_Select_Renderer extends Varien_Data_Form_Element_Select
{
    public function getElementHtml()
    {
        if (is_null($this->getValue())) {
            $this->setValue($this->getEntityAttribute()->getDefaultValue());
        }

        return parent::getElementHtml();
    }
}