<?php

class ErgonTech_SimpleProductAlert_Helper_PredicateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPredicate()
    {
        $refConfig = new ReflectionProperty(Mage::class, '_config');
        $refConfig->setAccessible(true);
        $refConfig->setValue(Mage::class, new Mage_Core_Model_Config_Base(<<<XML
<config>
    <simpleproductalert>
        <foo>
            <class>ErgonTech_SimpleProductAlert_CanSubscribePredicate</class>
        </foo>
    </simpleproductalert>
</config>
XML
        ));
        $subj = new ErgonTech_SimpleProductAlert_Helper_Predicate();

        static::assertTrue(is_callable($subj->getPredicate('foo')));
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
        </stock_predicate>
    </simpleproductalert>
</config>
XML
        ));
        $subj = new ErgonTech_SimpleProductAlert_Helper_Predicate();
        $this->setExpectedException(ErgonTech_SimpleProductAlert_Exception_ConfigurationException::class);
        $subj->getPredicate('stock_predicate');
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
        $subj = new ErgonTech_SimpleProductAlert_Helper_Predicate();
        $this->setExpectedException(ErgonTech_SimpleProductAlert_Exception_ConfigurationException::class);
        $subj->getPredicate('blah_blah');
    }

}