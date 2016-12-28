<?php
namespace LaravelShipStation\Models;

class Order
{
    /**
     * @var string A user-defined order number used to identify an order.
     */
    public $orderNumber;

    /**
     * @var string A user-provided key that should be unique to each order.
     */
    public $orderKey;

    /**
     * @var string The date the order was placed.
     */
    public $orderDate;

    /**
     * @var string The date the order was paid for.
     */
    public $paymentDate;

    /**
     * @var string The date the order is to be shipped before or on.
     */
    public $shipByDate;

    /**
     * @var string The order's status: "awaiting_payment", "awaiting_shipment", "shipped", "on_hold", "cancelled"
     */
    public $orderStatus;

    /**
     * @var string Identifier for the customer in the originating system. This is typically a username or email address.
     */
    public $customerUsername;

    /**
     * @var string The customer's email address.
     */
    public $customerEmail;

    /**
     * @var array The recipients billing address.
     */
    public $billTo;

    /**
     * @var array The recipients shipping address.
     */
    public $shipTo;

    /**
     * @var array Array of purchased items.
     */
    public $items;

    /**
     * @var float The total amount paid for the Order.
     */
    public $amountPaid;

    /**
     * @var float The total tax amount for the Order.
     */
    public $taxAmount;

    /**
     * @var float Shipping amount paid by the customer, if any.
     */
    public $shippingAmount;

    /**
     * @var string Notes left by the customer when placing the order.
     */
    public $customerNotes;

    /**
     * @var string Private notes that are only visible to the seller.
     */
    public $internalNotes;

    /**
     * @var bool Specifies whether or not this Order is a gift
     */
    public $gift;

    /**
     * @var string Gift message left by the customer when placing the order.
     */
    public $giftMessage;

    /**
     * @var string Method of payment used by the customer.
     */
    public $paymentMethod;

    /**
     * @var string Identifies the shipping service selected by the customer when placing this order.
     */
    public $requestedShippingService;

    /**
     * @var string The code for the carrier that is to be used(or was used) when this order is shipped(was shipped).
     */
    public $carrierCode;

    /**
     * @var string The code for the shipping service that is to be used(or was used) when this order is/was shipped.
     */
    public $serviceCode;

    /**
     * @var string The code for the package type that is to be used(or was used) when this order is/was shipped
     */
    public $packageCode;

    /**
     * @var string The type of delivery confirmation that is to be used(or was used) when this order is/was shipped
     */
    public $confirmation;

    /**
     * @var string The date the order was shipped.
     */
    public $shipDate;

    /**
     * @var string If placed on hold, this date is the expiration date for this order's hold status.
     * The order is moved back to awaiting_shipment on this date.
     */
    public $holdUntilDate;

    /**
     * @var array Weight of the order.
     */
    public $weight;

    /**
     * @var array Dimensions of the order.
     */
    public $dimensions;

    /**
     * @var array The shipping insurance information associated with this order.
     */
    public $insuranceOptions;

    /**
     * @var array Customs information that can be used to generate customs documents for international orders.
     */
    public $internationalOptions;

    /**
     * @var array Various advanced options that may be available depending on the shipping carrier
     * that is used to ship the order.
     */
    public $advancedOptions;

    /**
     * @var array Array of tagIds.Each tagId identifies a tag that has been associated with this order.
     */
    public $tagIds;
}
