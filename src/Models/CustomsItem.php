<?php
namespace LaravelShipStation\Models;

class CustomsItem
{
    /**
     * @var string A short description of the CustomItem
     */
    public $description;

    /**
     * @var int The quantity for this line item.
     */
    public $quantity;

    /**
     * @var float The value (in USD) of the line item.
     */
    public $value;

    /**
     * @var string The Harmonized Commodity Code for this line item.
     */
    public $harmonizedTariffCode;

    /**
     * @var string The 2-character ISO country code where the item originated.
     */
    public $countryOfOrigin;
}
