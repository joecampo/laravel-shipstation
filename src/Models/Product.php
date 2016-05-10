<?php
namespace LaravelShipStation\Models;

class Product
{
    /**
     * @var string Stock Keeping Unit. A user-defined value for a product to help identify
     * the product. It is suggested that each product should contain a unique SKU.
     */
    public $sku;

    /**
     * @var string Name or description of the product.
     */
    public $name;

    /**
     * @var float The unit price of the product.
     */
    public $price;

    /**
     * @var float The seller's cost for this product.
     */
    public $defaultCost;

    /**
     * @var int|float The length of the product. Unit of measurement is UI dependent.
     * No conversions will be made from one UOM to another. See our knowledge base
     */
    public $length;

    /**
     * @var int|float The length of the product. Unit of measurement is UI dependent.
     * No conversions will be made from one UOM to another. See our knowledge base
     */
    public $width;

    /**
     * @var int|float The length of the product. Unit of measurement is UI dependent.
     * No conversions will be made from one UOM to another. See our knowledge base
     */
    public $height;

    /**
     * @var float The weight of a single item in ounces.
     */
    public $weightOz;

    /**
     * @var string Sellers' private notes for the product.
     */
    public $internalNotes;

    /**
     * @var string Stock keeping Unit for the fulfillment of that product by a 3rd party.
     */
    public $fulfillmentSku;

    /**
     * @var bool Specifies whether or not the product is an active record.
     */
    public $active;

    /**
     * @var array The Product Category used to organize and report on similar products.
     */
    public $productCategory;

    /**
     * @var string Specifies the product type.
     */
    public $productType;

    /**
     * @var string The warehouse location associated with the product record.
     */
    public $warehouseLocation;

    /**
     * @var string The default domestic shipping carrier for this product.
     */
    public $defaultCarrierCode;

    /**
     * @var string The default domestic shipping service for this product.
     */
    public $defaultServiceCode;

    /**
     * @var string The default domestic packageType for this product.
     */
    public $defaultPackageCode;

    /**
     * @var string The default international shipping carrier for this product.
     */
    public $defaultIntlCarrierCode;

    /**
     * @var string The default international shipping service for this product.
     */
    public $defaultIntlServiceCode;

    /**
     * @var string The default international packageType for this product.
     */
    public $defaultIntlPackageCode;

    /**
     * @var string The default domestic Confirmation type for this Product.
     */
    public $defaultConfirmation;

    /**
     * @var string The default international Confirmation type for this Product.
     */
    public $defaultIntlConfirmation;

    /**
     * @var string The default customss Description for the product.
     */
    public $customsDescription;

    /**
     * @var float The default customs Declared Value for the product.
     */
    public $customsValue;

    /**
     * @var string The default "Harmonized Code for the Product.
     */
    public $customsTariffNo;
    /**
     * @var string The default 2 digit ISO Origin Country for the Product.
     */
    public $customsCountryCode;

    /**
     * @var bool If true, this product will not be included on the international custom forms.
     */
    public $noCustoms;

    /**
     * @var array The Product Tag used to organize and visually identify products.
     */
    public $tags;
}
