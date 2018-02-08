<?php

interface ErgonTech_SimpleProductAlert_PredicateInterface
{
    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public static function check(Mage_Catalog_Model_Product $product);
}