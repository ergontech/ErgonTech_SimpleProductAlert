<?php

class ErgonTech_SimpleProductAlert_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    public function testGetPredicate()
    {
        $refConfig = new ReflectionProperty(Mage::class, '_config');
        $refConfig->setAccessible(true);
        $refConfig->setValue(Mage::class, new Mage_Core_Model_Config_Base(<<<XML
<config>
    <simpleproductalert>
        <stock_predicate>
            <class>ErgonTech_SimpleProductAlert_StockPredicate</class>
            <function>check</function>
        </stock_predicate>
    </simpleproductalert>
</config>
XML
));
        $subj = new ErgonTech_SimpleProductAlert_Helper_Data();

        static::assertTrue(is_callable($subj->getStockPredicate()));
    }

    public function testGetPredicateWithInvalidData()
    {
        $refConfig = new ReflectionProperty(Mage::class, '_config');
        $refConfig->setAccessible(true);
        $refConfig->setValue(Mage::class, new Mage_Core_Model_Config_Base(<<<XML
<config>
    <simpleproductalert>
        <stock_predicate>
            <class>I_do_no_exist</class>
            <function>check</function>
        </stock_predicate>
    </simpleproductalert>
</config>
XML
));
        $subj = new ErgonTech_SimpleProductAlert_Helper_Data();
        $this->setExpectedException(ErgonTech_SimpleProductAlert_Exception_ConfigurationException::class);
        $subj->getStockPredicate();
    }

    public function testGetPredicateWithNoData()
    {
        $refConfig = new ReflectionProperty(Mage::class, '_config');
        $refConfig->setAccessible(true);
        $refConfig->setValue(Mage::class, new Mage_Core_Model_Config_Base(<<<XML
<config>
</config>
XML
));
        $subj = new ErgonTech_SimpleProductAlert_Helper_Data();
        $this->setExpectedException(ErgonTech_SimpleProductAlert_Exception_ConfigurationException::class);
        $subj->getStockPredicate();
    }
}
