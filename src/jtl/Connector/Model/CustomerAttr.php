<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Model
 */

namespace jtl\Connector\Model;

use \jtl\Core\Model\DataModel;

/**
 * CustomerAttr Model
 * @access public
 */
class CustomerAttr extends DataModel
{
    /**
     * @var int
     */
    protected $_id;
    
    /**
     * @var int
     */
    protected $_customerId;
    
    /**
     * @var string
     */
    protected $_key;
    
    /**
     * @var string
     */
    protected $_value;
    
    /**
     * CustomerAttr Setter
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case "_id":
            case "_customerId":
            
                $this->$name = (int)$value;
                break;
        
            case "_key":
            case "_value":
            
                $this->$name = (string)$value;
                break;
        
        }
    }
    
    /**
     * CustomerAttr Getter
     *
     * @param string $name
     */
    public function __get($name)
    {
        return $this->$name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \jtl\Core\Model\DataModel::map()
     */ 
    public function map($toWawi = false, \stdClass $obj = null)
    {
    
    }
}
?>