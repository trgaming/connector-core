<?php
/**
 * @copyright 2010-2014 JTL-Software GmbH
 * @package jtl\Connector\Type
 */

namespace jtl\Connector\Type;

use \jtl\Connector\Type\PropertyInfo;

/**
 * @access public
 * @package jtl\Connector\Type
 */
class ProductPriceItem extends DataType
{
    protected function loadProperties()
    {
        return array(
            new PropertyInfo('netPrice', 'double', null, false, false, false),
            new PropertyInfo('quantity', 'double', null, true, false, false),
        );
    }

	public function isMain()
	{
		return false;
	}
}