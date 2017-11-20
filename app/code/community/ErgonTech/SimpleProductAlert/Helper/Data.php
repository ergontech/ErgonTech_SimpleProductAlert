<?php

use ErgonTech_SimpleProductAlert_Exception_ConfigurationException as ConfigurationException;

class ErgonTech_SimpleProductAlert_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return callable
     * @throws Exception
     */
    public function getStockPredicate()
    {
        $config = Mage::getConfig()->getNode('simpleproductalert/stock_predicate');
        if (!$config || !is_callable($callable = [(string)$config->class, (string)$config->function])) {
            throw new ConfigurationException('Configured in stock predicate is not callable. Please check configuration.');
        }

        return $callable;
    }

    public function generateConfigurableAttributesAttrs(
        Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection $configurableAttributes,
        Mage_Catalog_Model_Product $child
    ) {
        $out = [];
        foreach ($configurableAttributes as $configurableAttribute) {
            /** @var Mage_Eav_Model_Entity_Attribute $productAttribute */
            $productAttribute = $configurableAttribute->getProductAttribute();
            $out[] = sprintf('data-%d="%s"',
                $productAttribute->getId(),
                $child->getData($productAttribute->getAttributeCode()));
        }

        return implode(' ', $out);
    }
}