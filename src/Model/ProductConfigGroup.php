<?php
/**
 * @copyright 2010-2015 JTL-Software GmbH
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 */

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Product-ConfigGroup Assignment.
 *
 * @access public
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 * @Serializer\AccessType("public_method")
 */
class ProductConfigGroup extends AbstractModel
{
    /**
     * @var Identity Reference to configGroup
     * @Serializer\Type("Jtl\Connector\Core\Model\Identity")
     * @Serializer\SerializedName("configGroupId")
     * @Serializer\Accessor(getter="getConfigGroupId",setter="setConfigGroupId")
     */
    protected $configGroupId = null;

    /**
     * @var integer Optional sort number
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("sort")
     * @Serializer\Accessor(getter="getSort",setter="setSort")
     */
    protected $sort = 0;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configGroupId = new Identity();
    }

    /**
     * @param Identity $configGroupId Reference to configGroup
     * @return ProductConfigGroup
     */
    public function setConfigGroupId(Identity $configGroupId): ProductConfigGroup
    {
        $this->configGroupId = $configGroupId;

        return $this;
    }

    /**
     * @return Identity Reference to configGroup
     */
    public function getConfigGroupId(): Identity
    {
        return $this->configGroupId;
    }

    /**
     * @param integer $sort Optional sort number
     * @return ProductConfigGroup
     */
    public function setSort(int $sort): ProductConfigGroup
    {
        $this->sort = $sort;
        
        return $this;
    }
    
    /**
     * @return integer Optional sort number
     */
    public function getSort(): int
    {
        return $this->sort;
    }
}
