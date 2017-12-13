<?php

class ErgonTech_SimpleProductAlert_Helper_Productalert_Data extends Mage_ProductAlert_Helper_Data
{
    /**
     * Allow products to override the enabledness of product stock alerts
     *
     * @return bool
     */
    public function isStockAlertAllowed()
    {
        $allowed = $this->getProduct()->getData('productalert_stock');

        if ($allowed === null || $allowed == 2) {
            return parent::isStockAlertAllowed();
        }

        return (bool)$allowed;
    }
}
