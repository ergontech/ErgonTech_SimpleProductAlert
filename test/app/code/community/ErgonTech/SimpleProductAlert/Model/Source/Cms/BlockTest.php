<?php


class ErgonTech_SimpleProductAlert_Model_Source_Cms_BlockTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $blockCollection = $this->prophesize(Mage_Cms_Model_Resource_Block_Collection::class);
        $blockCollection
            ->addFieldToFilter(\Prophecy\Argument::type('string'), \Prophecy\Argument::any())
            ->willReturn($blockCollection->reveal());

        $select = $this->prophesize(Varien_Db_Select::class);
        $select->reset(Zend_Db_Select::COLUMNS)
            ->willReturn($select->reveal());

        $select->columns(\Prophecy\Argument::type('array'))
            ->willReturn($select->reveal());

        $dbAdapter = $this->prophesize(Varien_Db_Adapter_Interface::class);
        $dbAdapter
            ->fetchPairs(\Prophecy\Argument::type(Zend_Db_Select::class))
            ->willReturn([
                1 => 'foo (bar)',
                2 => 'baz (qux)'
            ]);

        $blockCollection
            ->getConnection()
            ->willReturn($dbAdapter->reveal());

        $blockCollection
            ->getSelect()
            ->willReturn($select->reveal());

        Mage::register('_resource_singleton/cms/block_collection', $blockCollection->reveal());
    }

    public function testToOptionArray()
    {
        $subj = new ErgonTech_SimpleProductAlert_Model_Source_Cms_Block();
        $options = $subj->toOptionArray();
        static::assertContains(['label' => ' --- ', 'value' => null], $options);
        static::assertContains(['label' => 'foo (bar)', 'value' => 1], $options);
        static::assertContains(['label' => 'baz (qux)', 'value' => 2], $options);

        $optionsWithoutEmpty = $subj->toOptionArray(false);
        static::assertCount(2, $optionsWithoutEmpty);
        static::assertContains(['label' => 'foo (bar)', 'value' => 1], $optionsWithoutEmpty);
        static::assertContains(['label' => 'baz (qux)', 'value' => 2], $optionsWithoutEmpty);
    }
}
