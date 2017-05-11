<?php

class ErgonTech_SimpleProductAlert_Block_Configurable_Product_View extends Mage_ProductAlert_Block_Product_View
{
    public function prepareStockAlertData()
    {
        /** @var Mage_Catalog_Model_Product_Type_Configurable $configurableTypeInstance */
        $configurableTypeInstance = $this->_product->getTypeInstance();

        /** @var Mage_Catalog_Model_Product[] $childrenProducts */
        $childrenProducts = $configurableTypeInstance->getUsedProducts();

        /** @var Mage_ProductAlert_Block_Product_View $origStockInstance */
        $origStockInstance = $this->getChild('productalert_stock');

        foreach ($childrenProducts as $child) {
            $block = clone $origStockInstance;
            $block->_product = $child;
            $block->prepareStockAlertData();
        }
    }

    protected function _toHtml()
    {
        $html = '';
        foreach ($this->getChild() as $child) {
            /** @var $child Mage_ProductAlert_Block_Product_View */
            $html .= $child->toHtml();
        }

        return $html;
    }

}