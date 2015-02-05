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
class Identity extends DataType
{
    protected function loadProperties()
    {
		return array(
            new PropertyInfo('endpointId', 'string', '', false, false, false),
            new PropertyInfo('hostId', 'string', '', false, false, false),
		);
    }

	public function isMain()
	{
		return false;
	}
}