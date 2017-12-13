<?php

class ErgonTech_SimpleProductAlert_Helper_Productalert_Data extends Mage_ProductAlert_Helper_Data
{
    const PRODUCTALERT_STOCK_USE_CONFIG = 2;

    /**
     * Allow products to override the enabledness of product stock alerts
     *
     * @return bool
     */
    public function isStockAlertAllowed()
    {
        $allowed = $this->getProduct()->getData('productalert_stock');

        if ($allowed === null || $allowed == self::PRODUCTALERT_STOCK_USE_CONFIG) {
            return parent::isStockAlertAllowed();
        }

        return (bool)$allowed;
    }
}
