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
        $callable = [ (string)$config->class, (string)$config->function ];

        if (!is_callable($callable)) {
            throw new ConfigurationException('Configured in stock predicate is not callable. Please check configuration');
        }

        return $callable;
    }
}