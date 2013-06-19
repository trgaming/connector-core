<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\ModelContainer
 */

namespace jtl\Connector\ModelContainer;

/**
 * Image Container Class
 * @access public
 */
class ImageContainer extends CoreContainer
{
    /**
     * @var \jtl\Connector\Model\Image[]
     */
    protected $_images;
        
    /**
     * @return array \jtl\Connector\Model\Image
     */
    public function getImages()
    {
        return $this->_images;
    }
        
    public $items = array(
        "image" => array("Image", "Images")
    );
}
?>