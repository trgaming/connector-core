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
class CategoryI18n extends DataModel
{
    protected function loadProperties()
    {
        return array(
			'E479F72A' => new PropertyInfo('categoryId', '\jtl\Connector\Model\Identity', null, true, true, true),
			'A8E50D9E' => new PropertyInfo('description', 'string', '', false, false, false),
			'3BDDE85B' => new PropertyInfo('localeName', 'string', '', true, true, true),
			'03BD1C7B' => new PropertyInfo('name', 'string', '', false, false, false),
			'AD7DA7E9' => new PropertyInfo('url', 'string', '', false, false, false),
        );
    }
}


