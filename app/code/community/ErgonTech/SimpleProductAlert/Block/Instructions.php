<?php

class ErgonTech_SimpleProductAlert_Block_Instructions extends Mage_Core_Block_Template
{
    const XML_PATH_OOS_MODAL = 'catalog/productalert/oos_modal';

    const XML_PATH_OOS_HELPER_TEXT = 'catalog/productalert/oos_helper_text';

    public function getOosContent()
    {
        return Mage::getStoreConfig(self::XML_PATH_OOS_MODAL);
    }

    public function getOosHelperText()
    {
        return Mage::getStoreConfig(self::XML_PATH_OOS_HELPER_TEXT);
    }
}