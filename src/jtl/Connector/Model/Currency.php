<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Model
 */

namespace jtl\Connector\Model;

use \jtl\Core\Model\Model;
use \jtl\Core\Validator\Schema;

/**
 * Currency Model
 * @access public
 */
abstract class Currency extends Model
{
    /**
     * @var 
     */
    protected $_id;
    
    /**
     * @var int
     */
    protected $_name;
    
    /**
     * @var 
     */
    protected $_iso;
    
    /**
     * @var int
     */
    protected $_nameHtml;
    
    /**
     * @var double
     */
    protected $_factor;
    
    /**
     * @var string
     */
    protected $_default;
    
    /**
     * @var string
     */
    protected $_currencySignBeforeValue;
    
    /**
     * @var string
     */
    protected $_delimiterCent;
    
    /**
     * @param  $id
     * @return \jtl\Connector\Model\Currency
     */
    public function setId($id)
    {
        $this->_id = ()$id;
        return $this;
    }
    
    /**
     * @return 
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * @param int $name
     * @return \jtl\Connector\Model\Currency
     */
    public function setName($name)
    {
        $this->_name = (int)$name;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * @param  $iso
     * @return \jtl\Connector\Model\Currency
     */
    public function setIso($iso)
    {
        $this->_iso = ()$iso;
        return $this;
    }
    
    /**
     * @return 
     */
    public function getIso()
    {
        return $this->_iso;
    }
    
    /**
     * @param int $nameHtml
     * @return \jtl\Connector\Model\Currency
     */
    public function setNameHtml($nameHtml)
    {
        $this->_nameHtml = (int)$nameHtml;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNameHtml()
    {
        return $this->_nameHtml;
    }
    
    /**
     * @param double $factor
     * @return \jtl\Connector\Model\Currency
     */
    public function setFactor($factor)
    {
        $this->_factor = (double)$factor;
        return $this;
    }
    
    /**
     * @return double
     */
    public function getFactor()
    {
        return $this->_factor;
    }
    
    /**
     * @param string $default
     * @return \jtl\Connector\Model\Currency
     */
    public function setDefault($default)
    {
        $this->_default = (string)$default;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->_default;
    }
    
    /**
     * @param string $currencySignBeforeValue
     * @return \jtl\Connector\Model\Currency
     */
    public function setCurrencySignBeforeValue($currencySignBeforeValue)
    {
        $this->_currencySignBeforeValue = (string)$currencySignBeforeValue;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCurrencySignBeforeValue()
    {
        return $this->_currencySignBeforeValue;
    }
    
    /**
     * @param string $delimiterCent
     * @return \jtl\Connector\Model\Currency
     */
    public function setDelimiterCent($delimiterCent)
    {
        $this->_delimiterCent = (string)$delimiterCent;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDelimiterCent()
    {
        return $this->_delimiterCent;
    }
    
    /**
     * (non-PHPdoc)
     * @see \jtl\Core\Model\Model::validate()
     */
    public function validate()
    {
        Schema::validateModel(CONNECTOR_DIR . "schema/Currency/Currency.json", $this->getPublic(array()));
    }
}
?>