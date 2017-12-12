<?php

class ErgonTech_SimpleProductAlert_Model_Source_Cms_Block
{
    /** @var array */
    protected $optionArray;

    /** @var array */
    protected $pairs;

    /**
     * @param bool $withEmpty
     * @return array
     */
    public function toOptionArray($withEmpty = true)
    {
        $pairs = $this->toArray();

        if ($this->optionArray === null) {
            $this->optionArray = [];
            foreach ($pairs as $blockId => $name) {
                $this->optionArray[] = [
                    'label' => $name,
                    'value' => $blockId
                ];
            }
        }

        $optionArray = $this->optionArray;

        if ($withEmpty) {
            array_unshift($optionArray, [
                'label' => ' --- ',
                'value' => null
            ]);
        }

        return $optionArray;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->pairs === null) {
            /** @var Mage_Cms_Model_Resource_Block_Collection $blockResourceCollection */
            $blockResourceCollection = Mage::getResourceModel('cms/block_collection');
            $blockResourceCollection->addFieldToFilter('is_active', true);

            $blocksSelect = $blockResourceCollection->getSelect();
            $blocksSelect->reset(Zend_Db_Select::COLUMNS)
                ->columns(['block_id', "CONCAT(title, ' (', identifier, ')')"]);

            $this->pairs = $blockResourceCollection->getConnection()->fetchPairs($blocksSelect);
        }

        return $this->pairs;
    }
}
