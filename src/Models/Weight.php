<?php
namespace LaravelShipStation\Models;

class Weight
{
    /**
     * @var float|int Weight value.
     */
    public $value;

    /**
     * @var string Units of weight. Allowed units are: "pounds", "ounces", or "grams"
     */
    public $units;
}
