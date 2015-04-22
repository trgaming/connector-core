<?php
namespace jtl\Connector\Event\ProductPrice;

use \Symfony\Component\EventDispatcher\Event;
use \jtl\Connector\Model\ProductPrice;

class ProductPriceAfterDeleteEvent extends Event
{
    const EVENT_NAME = 'productPrice.after.delete';

    protected $productPrice;
    
    public function __construct(ProductPrice &$productPrice)
    {
        $this->productPrice = $productPrice;    
    }

    public function getProductPrice()
    {
        return $this->productPrice;    
    }
}
