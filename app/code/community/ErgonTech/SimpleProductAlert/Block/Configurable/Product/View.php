<?php

class ErgonTech_SimpleProductAlert_Block_Configurable_Product_View extends Mage_ProductAlert_Block_Product_View
{
    public function prepareStockAlertData()
    {
        /** @var Mage_Catalog_Model_Product_Type_Configurable $configurableTypeInstance */
        $configurableTypeInstance = $this->_product->getTypeInstance();

        /** @var Mage_Catalog_Model_Product[] $childrenProducts */
        $childrenProducts = $configurableTypeInstance->getUsedProducts();

        $configurableAttributes = $configurableTypeInstance->getConfigurableAttributes($this->_product);

        /** @var Mage_ProductAlert_Block_Product_View $origStockInstance */
        $origStockInstance = $this->getChild('productalert_stock');
        $this->unsetChild('productalert_stock');

        $helper = Mage::helper('productalert');

        /** @var ErgonTech_SimpleProductAlert_Helper_Data $simpleProductAlertHelper */
        $simpleProductAlertHelper = Mage::helper('simpleproductalert');
        $childrenProducts = array_filter($childrenProducts, $simpleProductAlertHelper->getStockPredicate());

        $notifyLinks = $this->getLayout()->createBlock('core/text_list', 'notify_links');

        foreach ($childrenProducts as $i => $child) {
            /** @var Mage_ProductAlert_Block_Product_View $block */
            $block = $this->getLayout()->createBlock('productalert/product_view', 'simplechild_' . $i);
            $block->_product = $child;
            $block->setNameInLayout("simplechild_{$i}");
            $block->setTemplate($origStockInstance->getTemplate());
            $block->setData($origStockInstance->getData());
            $block->setData('signup_label', $this->getSignupLabel());
            $block->setData('configurable_attributes_attrs',
                $simpleProductAlertHelper->generateConfigurableAttributesAttrs($configurableAttributes, $child));

            $helper->setProduct($child);
            $block->setData('signup_url', $helper->getSaveUrl('stock'));

            $notifyLinks->insert($block);
        }
        $this->setChild('notify_links', $notifyLinks);
        $helper->setProduct(Mage::registry('current_product'));
    }

    protected function _toHtml()
    {
        return $this->getChildHtml('notify_links');
    }

    protected function _afterToHtml($html)
    {
        if ($html === '') {
            return '';
        }

        return $html . $this->getChildHtml('instructions');
    }
}