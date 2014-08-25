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
class SpecificValue extends DataType
{
    protected function loadProperties()
    {
        return array(
            new PropertyInfo('id', 'int', null, true, true, false),
            new PropertyInfo('sort', 'int', null, false, false, false),
            new PropertyInfo('specificId', 'int', null, false, true, false),
            new PropertyInfo('i18ns', '\jtl\Connector\Model\SpecificValueI18n', null, false, false, true),
        );
    }
}