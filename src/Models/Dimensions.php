<?php
namespace LaravelShipStation\Models;

class Dimensions
{
    /**
     * @var int|float Length of package.
     */
    public $length;

    /**
     * @var int|float Width of package.
     */
    public $width;

    /**
     * @var int|float Height of package.
     */
    public $height;

    /**
     * @var'string Units of measurement. Allowed units are: "inches", or "centimeters".
     */
    public $units;
}
