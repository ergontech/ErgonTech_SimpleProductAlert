<?php

use ErgonTech_SimpleProductAlert_sql_simpleproductalert_setup_InstallerTest as InstallerTest;
use Prophecy\Argument as Arg;

const INSTALLER_PATH = __DIR__ . '/../../../../../../../../app/code/community/ErgonTech/SimpleProductAlert/sql/simpleproductalert_setup/install-0.1.0.php';
class ErgonTech_SimpleProductAlert_sql_simpleproductalert_setup_InstallerTest extends PHPUnit_Framework_TestCase
{
    public function testInstall()
    {
        /**
         * @param \Prophecy\Call\Call[] $calls
         */
        $assertions = function ($calls) {
            list($entityType, $attrcode, $attrData) = $calls[0]->getArguments();

            InstallerTest::assertEquals('catalog_product', $entityType);
            InstallerTest::assertEquals('productalert_stock', $attrcode);

            InstallerTest::assertArraySubset(['input' => 'select'], $attrData);
            InstallerTest::assertArraySubset(['label' => 'Allow stock notifications for product?'], $attrData);
            InstallerTest::assertArraySubset(['source' => 'catalog/product_attribute_source_boolean'], $attrData);
            InstallerTest::assertArraySubset(['required' => false], $attrData);
            InstallerTest::assertArraySubset(['global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE], $attrData);
            InstallerTest::assertArraySubset(['default' => 1], $attrData);
            InstallerTest::assertArraySubset(['apply_to' => 'configurable,simple'], $attrData);
        };

        $installer = $this->prophesize(Mage_Catalog_Model_Resource_Setup::class);
        $installer
            ->addAttribute(Arg::type('string'), Arg::type('string'), Arg::type('array'))
            ->should($assertions);

        $installer->startSetup()->shouldBeCalled();
        $installer->endSetup()->shouldBeCalled();

        // Change context away from the test and to the mocked installer
        $include = function () { include INSTALLER_PATH; };
        call_user_func($include->bindTo($installer->reveal()));
    }
}