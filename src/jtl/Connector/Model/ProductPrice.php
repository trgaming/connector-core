<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Model
 */

namespace jtl\Connector\Model;

use \jtl\Core\Model\DataModel;

/**
 * ProductPrice Model
 * Product price properties.
 *
 * @access public
 */
class ProductPrice extends DataModel
{
    /**
     * @var string Reference to customerGroup
     */
    protected $_customerGroupId = '';
    
    /**
     * @var string Reference to product
     */
    protected $_productId = '';
    
    /**
     * @var double Price value (net)
     */
    protected $_netPrice = 0.0;
    
    /**
     * @var int Optional quantity to apply netPrice for. Default 1 for default price. A quantity value of 3 means that the given product price will be applied when a customer buys 3 or more items. 
     */
    protected $_quantity = 1;
    
    /**
     * ProductPrice Setter
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if ($value === null) {
                $this->$name = null;
                return;
            }
        
            switch ($name) {
                case "_customerGroupId":
                case "_productId":
                
                    $this->$name = (string)$value;
                    break;
            
                case "_netPrice":
                
                    $this->$name = (double)$value;
                    break;
            
                case "_quantity":
                
                    $this->$name = (int)$value;
                    break;
            
            }
        }
    }
    
    /**
     * @param string $customerGroupId Reference to customerGroup
     * @return \jtl\Connector\Model\ProductPrice
     */
    public function setCustomerGroupId($customerGroupId)
    {
        $this->_customerGroupId = (string)$customerGroupId;
        return $this;
    }
    
    /**
     * @return string Reference to customerGroup
     */
    public function getCustomerGroupId()
    {
        return $this->_customerGroupId;
    }
    /**
     * @param string $productId Reference to product
     * @return \jtl\Connector\Model\ProductPrice
     */
    public function setProductId($productId)
    {
        $this->_productId = (string)$productId;
        return $this;
    }
    
    /**
     * @return string Reference to product
     */
    public function getProductId()
    {
        return $this->_productId;
    }
    /**
     * @param double $netPrice Price value (net)
     * @return \jtl\Connector\Model\ProductPrice
     */
    public function setNetPrice($netPrice)
    {
        $this->_netPrice = (double)$netPrice;
        return $this;
    }
    
    /**
     * @return double Price value (net)
     */
    public function getNetPrice()
    {
        return $this->_netPrice;
    }
    /**
     * @param int $quantity Optional quantity to apply netPrice for. Default 1 for default price. A quantity value of 3 means that the given product price will be applied when a customer buys 3 or more items. 
     * @return \jtl\Connector\Model\ProductPrice
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = (int)$quantity;
        return $this;
    }
    
    /**
     * @return int Optional quantity to apply netPrice for. Default 1 for default price. A quantity value of 3 means that the given product price will be applied when a customer buys 3 or more items. 
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }
}