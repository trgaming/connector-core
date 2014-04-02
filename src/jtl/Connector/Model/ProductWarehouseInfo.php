<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Model
 * @subpackage Product
 */

namespace jtl\Connector\Model;

use \jtl\Core\Model\DataModel;

/**
 * Product to warehouse info association.
 *
 * @access public
 * @subpackage Product
 */
class ProductWarehouseInfo extends DataModel
{
    /**
     * @var string Reference to product
     */
    protected $_productId = '';             
    
    /**
     * @var string Reference to warehouse
     */
    protected $_warehouseId = '';             
    
    /**
     * @var double Optional product stock level in specified warehouse
     */
    protected $_stockLevel = 0;             
    
    /**
     * @var double Optional product inflow quantity for specified warehouse
     */
    protected $_inflowQuantity = 0;             
    
    /**
     * @var string Optional product inflow date for specified warehouse
     */
    protected $_inflowDate = null;             
    
    /**
     * ProductWarehouseInfo Setter
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
                case "_productId":
                case "_warehouseId":
                case "_inflowDate":
                
                    $this->$name = (string)$value;
                    break;
            
                case "_stockLevel":
                case "_inflowQuantity":
                
                    $this->$name = (double)$value;
                    break;
            
            }
        }
    }
    
    /**
     * @param string $productId Reference to product
     * @return \jtl\Connector\Model\ProductWarehouseInfo
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
     * @param string $warehouseId Reference to warehouse
     * @return \jtl\Connector\Model\ProductWarehouseInfo
     */
    public function setWarehouseId($warehouseId)
    {
        $this->_warehouseId = (string)$warehouseId;
        return $this;
    }
    
    /**
     * @return string Reference to warehouse
     */
    public function getWarehouseId()
    {
        return $this->_warehouseId;
    }
    /**
     * @param double $stockLevel Optional product stock level in specified warehouse
     * @return \jtl\Connector\Model\ProductWarehouseInfo
     */
    public function setStockLevel($stockLevel)
    {
        $this->_stockLevel = (double)$stockLevel;
        return $this;
    }
    
    /**
     * @return double Optional product stock level in specified warehouse
     */
    public function getStockLevel()
    {
        return $this->_stockLevel;
    }
    /**
     * @param double $inflowQuantity Optional product inflow quantity for specified warehouse
     * @return \jtl\Connector\Model\ProductWarehouseInfo
     */
    public function setInflowQuantity($inflowQuantity)
    {
        $this->_inflowQuantity = (double)$inflowQuantity;
        return $this;
    }
    
    /**
     * @return double Optional product inflow quantity for specified warehouse
     */
    public function getInflowQuantity()
    {
        return $this->_inflowQuantity;
    }
    /**
     * @param string $inflowDate Optional product inflow date for specified warehouse
     * @return \jtl\Connector\Model\ProductWarehouseInfo
     */
    public function setInflowDate($inflowDate)
    {
        $this->_inflowDate = (string)$inflowDate;
        return $this;
    }
    
    /**
     * @return string Optional product inflow date for specified warehouse
     */
    public function getInflowDate()
    {
        return $this->_inflowDate;
    }
}