<?php
/**
 * @copyright 2010-2014 JTL-Software GmbH
 * @package jtl\Connector\Type
 */

namespace jtl\Connector\Type;

use jtl\Connector\Type\PropertyInfo;

/**
 * @access public
 * @package jtl\Connector\Type
 */
class CustomerGroupI18n extends DataModel
{
    protected function loadProperties()
    {
        return array(
			'C5988257' => new PropertyInfo('customerGroupId', '\jtl\Connector\Model\Identity', null, true, true, true),
			'3BDDE85B' => new PropertyInfo('localeName', 'string', '', true, true, true),
			'03BD1C7B' => new PropertyInfo('name', 'string', '', false, false, false),
        );
    }
}


