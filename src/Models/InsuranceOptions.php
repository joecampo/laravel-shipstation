<?php
namespace LaravelShipStation\Models;

class InsuranceOptions
{
    /**
     * @var string Preferred Insurance provider. Available options: "shipsurance" or "carrier"
     */
    public $provider;

    /**
     * @var bool Indicates whether shipment should be insured.
     */
    public $insureShipment;

    /**
     * @var float Value to insure.
     */
    public $insuredValue;
}
