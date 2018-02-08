<?php

class ErgonTech_SimpleProductAlert_Model_ProductAlert_Observer extends Mage_ProductAlert_Model_Observer
{
    /**
     * Process stock emails
     *
     * @param Mage_ProductAlert_Model_Email $email
     * @return Mage_ProductAlert_Model_Observer
     */
    protected function _processStock(Mage_ProductAlert_Model_Email $email)
    {
        $email->setType('stock');
        $originalStore = Mage::app()->getStore();

        /**
         * @author Matthew Wells <matthew@ergon.tech>
         * Get some variables ready for OVERRIDE
         */
        /** @var callable $canNotify */
        $canNotify = Mage::helper('simpleproductalert/predicate')->getPredicate('notify');

        /** @var ErgonTech_SimpleProductAlert_Helper_Data $productAlertHelper */
        $productAlertHelper = Mage::helper('productalert');

        foreach ($this->_getWebsites() as $website) {
            /* @var $website Mage_Core_Model_Website */

            if (!$website->getDefaultGroup() || !$website->getDefaultGroup()->getDefaultStore()) {
                continue;
            }
            if (!Mage::getStoreConfig(
                self::XML_PATH_STOCK_ALLOW,
                $website->getDefaultGroup()->getDefaultStore()->getId()
            )) {
                continue;
            }
            try {
                $collection = Mage::getModel('productalert/stock')
                    ->getCollection()
                    ->addWebsiteFilter($website->getId())
                    ->addStatusFilter(0)
                    ->setCustomerOrder();
            }
            catch (Exception $e) {
                $this->_errors[] = $e->getMessage();
                return $this;
            }

            $previousCustomer = null;
            $email->setWebsite($website);
            Mage::app()->setCurrentStore($website->getDefaultGroup()->getDefaultStore());
            foreach ($collection as $alert) {
                try {
                    if (!$previousCustomer || $previousCustomer->getId() != $alert->getCustomerId()) {
                        $customer = Mage::getModel('customer/customer')->load($alert->getCustomerId());
                        if ($previousCustomer) {
                            $email->send();
                        }
                        if (!$customer) {
                            continue;
                        }
                        $previousCustomer = $customer;
                        $email->clean();
                        $email->setCustomer($customer);
                    }
                    else {
                        $customer = $previousCustomer;
                    }

                    $product = Mage::getModel('catalog/product')
                        ->setStoreId($website->getDefaultStore()->getId())
                        ->load($alert->getProductId());
                    /* @var $product Mage_Catalog_Model_Product */
                    if (!$product) {
                        continue;
                    }

                    /**
                     * @author Matthew Wells <matthew@ergon.tech>
                     * Set the current product in the product alert helper
                     */
                    $productAlertHelper->setProduct($product);

                    $product->setCustomerGroupId($customer->getGroupId());

                    /**
                     * @author Matthew Wells <matthew@ergon.tech>
                     * Use the stock predicate, reflecting the inverse of "can I sign up?"
                     */
                    if ($canNotify($product)) {
                        $email->addStockProduct($product);

                        $alert->setSendDate(Mage::getModel('core/date')->gmtDate());
                        $alert->setSendCount($alert->getSendCount() + 1);
                        $alert->setStatus(1);
                        $alert->save();
                    }
                }
                catch (Exception $e) {
                    $this->_errors[] = $e->getMessage();
                }
            }

            if ($previousCustomer) {
                try {
                    $email->send();
                }
                catch (Exception $e) {
                    $this->_errors[] = $e->getMessage();
                }
            }
        }
        Mage::app()->setCurrentStore($originalStore);

        return $this;
    }
}