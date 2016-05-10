<?php
//@codingStandardsIgnoreStart
class ShipStationTest extends PHPUnit_Framework_TestCase
{

    protected $shipStation;

    public function setUp()
    {
        $testOption = getenv('SHIPSTATION_TESTING');

        if (empty($testOption) || $testOption == false) {
            echo "ShipStation tests are currently disabled in your phpunit.xml" . PHP_EOL;
            echo "Tests will create an order in your production environment and delete it." . PHP_EOL;
            echo "To enable tests set the env variable SHIPSTATION_TESTING to true in phpunit.xml." . PHP_EOL;

            exit();
        }

        if(empty(getenv('KEY')) || empty(getenv('SECRET'))) {
            echo "Please set your API Key & Secret Key in phpunit.xml" . PHP_EOL;
            exit();
        }

        $this->shipStation = new LaravelShipStation\ShipStation(getenv('KEY'), getenv('SECRET'));
    }

    /**
     * @test
     */
    public function endpoint_can_be_set()
    {
        $this->shipStation->shipments;

        $this->assertEquals('/shipments/', $this->shipStation->endpoint);
    }

    /**
     * @test
     */
    public function order_can_be_created()
    {
        $address = new LaravelShipStation\Models\Address();

        $address->name = "Joe Campo";
        $address->street1 = "123 Main St";
        $address->city = "Cleveland";
        $address->state = "OH";
        $address->postalCode = "44127";
        $address->country = "US";
        $address->phone = "2165555555";

        $item = new LaravelShipStation\Models\OrderItem();

        $item->lineItemKey = '1';
        $item->sku = '580123456';
        $item->name = "Test product";
        $item->quantity = '1';
        $item->unitPrice  = '29.99';
        $item->warehouseLocation = 'Warehouse A';

        $order = new LaravelShipStation\Models\Order();

        $order->orderNumber = 'TestOrder';
        $order->orderDate = '1999-01-01';
        $order->orderStatus = 'awaiting_shipment';
        $order->amountPaid = '0.00';
        $order->taxAmount = '0.00';
        $order->shippingAmount = '0.00';
        $order->internalNotes = 'A note about my order.';
        $order->billTo = $address;
        $order->shipTo = $address;
        $order->items[] = $item;

        $newOrder = $this->shipStation->orders->create($order);

        $this->assertEquals('TestOrder', $newOrder->orderNumber);
    }

    /**
     * @test
     */
    public function order_does_exist()
    {
        $this->assertTrue($this->shipStation->orders->exists('TestOrder'));
    }

    /**
     * @test
     */
    public function order_has_internal_note()
    {
        $order = $this->shipStation->orders->get(['orderNumber' => 'TestOrder']);

        $this->assertEquals('A note about my order.', $order->orders[0]->internalNotes);
    }

    /**
     * @test
     */
    public function orders_are_awaiting_shipments()
    {
        $this->assertGreaterThan(0, $this->shipStation->orders->awaitingShipmentCount());
    }

    /**
     * @test
     */
    public function order_is_deleted()
    {

        $orderId = $this->shipStation->orders->get(['orderNumber' => 'TestOrder'])->orders[0]->orderId;

        $this->shipStation->orders->delete($orderId);

        $this->assertFalse($this->shipStation->orders->exists('TestOrder'));
    }
}
