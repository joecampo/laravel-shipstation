<?php
namespace LaravelShipStation\Models;

class Address
{
    /**
     * @var string Name of person
     */
    public $name;

    /**
     * @var string Name of company
     */
    public $company;

    /**
     * @var string First line of address
     */
    public $street1;

    /**
     * @var string Second line of address
     */
    public $street2;

    /**
     * @var string Third line of address
     */
    public $street3;

    /**
     * @var string City
     */
    public $city;

    /**
     * @var string State
     */
    public $state;

    /**
     * @var string Postal Code
     */
    public $postalCode;

    /**
     * @var string Country Code. The two-cahracter ISO country code is required.
     */
    public $country;

    /**
     * @var string Telephone number.
     */
    public $phone;

    /**
     * @var bool Specifies whether the given address is residential.
     */
    public $residential;
}
