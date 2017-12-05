<?php

class ErgonTech_SimpleProductAlerts_etc_SystemTest extends \MageTest_PHPUnit_Framework_TestCase
{
    const GROUP_NODE_XPATH = 'sections/catalog/groups/productalert';
    use \ErgonTech\MageTest\LayoutHelpers;
    /** @var  Mage_Core_Model_Config_Base */
    protected $config;

    public static function assertFieldShownInScopes(array $scopes, Varien_Simplexml_Element $node)
    {
        foreach ($scopes as $scope) {
            static::assertXpathHasResults( $node, 'show_in_' . $scope . '[.=1]');
        }
    }

    public static function assertNodeIsTranslated($nodeXpath, Varien_Simplexml_Element $containerNode)
    {
        $nodes = $containerNode->xpath($nodeXpath);
        foreach ($nodes as $node) {
            /** @var Varien_Simplexml_Element $node */
            /** @var Varien_Simplexml_Element $parent */
            $parent = $node->getParent();
            $nodeName = $node->getName();
            static::assertNotNull($parent->getAttribute('translate'), $parent->asNiceXml() .
                " has a <{$nodeName}> child node which was not translated");
            static::assertContains($nodeName, $parent->getAttribute('translate'));
        }
    }

    public function setUp()
    {
        $this->config = new \Mage_Core_Model_Config_Base(__DIR__ . '/../../../../../../../app/code/community/ErgonTech/SimpleProductAlert/etc/system.xml');
    }

    public function testFieldsHaveLabels()
    {
        $fieldXpath = static::GROUP_NODE_XPATH . '/fields';

        static::assertXpathHasResults($this->config->getNode(), $fieldXpath);
        $nodes = current($this->config->getNode()->xpath($fieldXpath));

        foreach ($nodes as $node) {
            static::assertXpathHasResults($node, 'label');
        }
    }

    public function testFieldOrder()
    {
        // assert sequentially placed nodes have sort_orders which increase correspondingly

        $nodes = $this->config->getNode()->xpath(static::GROUP_NODE_XPATH . '/fields/*');

        static::assertNotEmpty($nodes);
        $previousOrder = 0;
        foreach ($nodes as $node) {
            static::assertXpathHasResults($node, 'sort_order');
            $order = (int)current($node->xpath('sort_order'));
            static::assertGreaterThan($previousOrder, $order);
            $previousOrder = $order;
        }
    }

    public function testSystemXmlPath()
    {
        static::assertEquals('config', $this->config->getNode()->getName());
        static::assertXpathHasResults($this->config->getNode(), self::GROUP_NODE_XPATH);
    }

    public function testSimpleAlertsEnabledNode()
    {
        $enabledFieldXpath = self::GROUP_NODE_XPATH . '/fields/enabled';

        // assert node exists
        static::assertXpathHasResults($this->config->getNode(), $enabledFieldXpath);
        $node = current($this->config->getNode()->xpath($enabledFieldXpath));

        // assert it's in the right scopes
        static::assertFieldShownInScopes(['store', 'default', 'website'], $node);

        // assert it's a yes/no select
        static::assertXpathHasResults($node,  'frontend_type[.="select"]');
        static::assertXpathHasResults($node, 'source_model[.="adminhtml/system_config_source_yesno"]');
    }

    public function testOosTextNode()
    {
        $oosTextXpath = self::GROUP_NODE_XPATH . '/fields/oos_helper_text';

        // assert node exists
        static::assertXpathHasResults($this->config->getNode(), $oosTextXpath);
        $node = current($this->config->getNode()->xpath($oosTextXpath));

        // assert it's in the right scopes
        static::assertFieldShownInScopes(['store', 'default', 'website'], $node);

        // assert it's a yes/no select
        static::assertXpathHasResults($node,  'frontend_type[.="text"]');

        // assert it depends on the enabled field
        static::assertXpathHasResults($node, 'depends/enabled[.=1]');
    }

    public function testOosModalNode()
    {
        $oosModalXpath = self::GROUP_NODE_XPATH . '/fields/oos_modal';

        // assert node exists
        static::assertXpathHasResults($this->config->getNode(), $oosModalXpath);
        $node = current($this->config->getNode()->xpath($oosModalXpath));

        // assert it's in the right scopes
        static::assertFieldShownInScopes(['store', 'default', 'website'], $node);

        // assert it's a cms block select
        static::assertXpathHasResults($node,  'frontend_type[.="select"]');
        static::assertXpathHasResults($node, 'source_model[.="simpleproductalert/source_cms_block"]');

        // assert it depends on the enabled field
        static::assertXpathHasResults($node, 'depends/enabled[.=1]');
    }

    public function testAllLabelsAreTranslated()
    {
        static::assertNodeIsTranslated('//label', $this->config->getNode());
    }
}
