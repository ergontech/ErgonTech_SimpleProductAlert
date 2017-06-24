<?php

class ErgonTech_SimpleProductAlert_StockPredicate
{
    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public static function check(Mage_Catalog_Model_Product $product)
    {
        $isAvailable = $product->isAvailable();
        $isStockAlertAllowed = Mage::helper('productalert')->isStockAlertAllowed();
        return $isStockAlertAllowed && !$isAvailable;
    }
}