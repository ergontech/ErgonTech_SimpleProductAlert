<?php

class ErgonTech_SimpleProductAlert_Block_Instructions extends Mage_Core_Block_Template
{
    const XML_PATH_OOS_MODAL = 'catalog/productalert/oos_modal';

    const XML_PATH_OOS_HELPER_TEXT = 'catalog/productalert/oos_helper_text';

    /** @var string */
    protected $oosContent;

    public function getOosContent()
    {
        if ($this->oosContent === null) {
            $block = Mage::getModel('cms/block');
            $this->oosContent = $block->load(Mage::getStoreConfig(self::XML_PATH_OOS_MODAL))->getContent();
        }

        return $this->oosContent;
    }

    public function getOosHelperText()
    {
        return Mage::getStoreConfig(self::XML_PATH_OOS_HELPER_TEXT);
    }
}