<?php

class ErgonTech_SimpleProductAlert_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateConfigurableAttributesAttr()
    {
        $subj = new ErgonTech_SimpleProductAlert_Helper_Data();
        $attrs = $this->prophesize(Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection::class);
        $attrs->getIterator()->willReturn(new ArrayIterator([
            new Varien_Object([
                'product_attribute' => new Varien_Object([
                    'id' => '1',
                    'attribute_code' => 'test'
                ])
            ]),
            new Varien_Object([
                'product_attribute' => new Varien_Object([
                    'id' => '2',
                    'attribute_code' => 'other'
                ])
            ])
        ]));
        $child = $this->prophesize(Mage_Catalog_Model_Product::class);
        $child->getData('test')->willReturn('42');
        $child->getData('other')->willReturn('0');

        $res = $subj->generateConfigurableAttributesAttrs($attrs->reveal(), $child->reveal());

        static::assertEquals('data-1="42" data-2="0"', $res);
    }
}
