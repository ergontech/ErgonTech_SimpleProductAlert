<?php

use ErgonTech_SimpleProductAlert_Exception_ConfigurationException as ConfigurationException;

class ErgonTech_SimpleProductAlert_Helper_Predicate extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $type
     * @return callable
     * @throws ErgonTech_SimpleProductAlert_Exception_ConfigurationException
     */
    public function getPredicate($type)
    {
        $config = Mage::getConfig()->getNode("simpleproductalert/{$type}");
        if (!$config || !is_callable($callable = [(string)$config->class, 'check'])) {
            throw new ConfigurationException('Configured in stock predicate is not callable. Please check configuration.');
        }

        return $callable;
    }

}