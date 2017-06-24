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
        $this->unsetChild('productalert_stock');

        $helper = Mage::helper('productalert');

        /** @var ErgonTech_SimpleProductAlert_Helper_Data $simpleProductAlertHelper */
        $simpleProductAlertHelper = Mage::helper('simpleproductalert');
        $childrenProducts = array_filter($childrenProducts, $simpleProductAlertHelper->getStockPredicate());

        foreach ($childrenProducts as $i => $child) {
            $block = $this->getLayout()->createBlock('productalert/product_view', 'simplechild_' . $i);
            $block->_product = $child;
            $block->setNameInLayout("simplechild_{$i}");
            $block->setTemplate($origStockInstance->getTemplate());
            $block->setData('signup_label', $this->getSignupLabel());

            $helper->setProduct($child);
            $block->setData('signup_url', $helper->getSaveUrl('stock'));

            $this->setChild("mychild{$i}", $block);
        }
        $helper->setProduct(Mage::registry('current_product'));
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