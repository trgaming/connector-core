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
class TaxRate extends DataType
{
    protected function loadProperties()
    {
        return array(
            new PropertyInfo('id', 'Identity', null, true, true, false),
            new PropertyInfo('taxClassId', 'Identity', null, false, true, false),
            new PropertyInfo('taxZoneId', 'Identity', null, false, true, false),
            new PropertyInfo('priority', 'string', '', false, false, false),
            new PropertyInfo('rate', 'double', 0.0, false, false, false),
        );
    }

    public function isMain()
    {
        return false;
    }
}
