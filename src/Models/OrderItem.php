<?php
namespace LaravelShipStation\Models;

class OrderItem
{

    /**
     * @var string An identifier for the OrderItem in the originating system.
     */
    public $lineItemKey;

    /**
     * @var string The SKU (stock keeping unit) identifier for the product associated with this line item.
     */
    public $sku;

    /**
     * @var string The name of the product associated with this line item.
     */
    public $name;

    /**
     * @var string The public URL to the product image.
     */
    public $imageUrl;

    /**
     * @var array The weight of a single item.
     */
    public $weight;

    /**
     * @var int The quantity of the product ordered.
     */
    public $quantity;

    /**
     * @var float The sell price of a single item specified by the order source.
     */
    public $unitPrice;

    /**
     * @var float The tax price of a single item specified by the order source.
     */
    public $taxAmount;

    /**
     * @var float The shipping amount or price of a single item specified by the order source.
     */
    public $shippingAmount;

    /**
     * @var string The location of the product within the seller's warehouse (e.g. Aisle 3, Shelf A, Bin 5)
     */
    public $warehouseLocation;

    /**
     * @var array
     */
    public $options;

    /**
     * @var int The identifier for the PRoduct Rsource associated with this OrderItem.
     */
    public $productId;

    /**
     * @var string The fulfillment SKU associated with this OrderItem if the fulfillment provider requires an
     * identifier other than the SKU.
     */
    public $fulfillmentSku;

    /**
     * @var bool Indicates that the OrderItem is a non-physical adjustment to the order
     * (e.g. a discount or promotional code).
     */
    public $adjustment = false;

    /**
     * @var string UPC associated with this OrderItem.
     */
    public $upc;
}
