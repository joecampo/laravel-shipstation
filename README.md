LaravelShipStation
===============
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][packagist-downloads]][link-packagist]

This is a simple PHP API wrapper for [ShipStation](http://shipstation.com) built for Laravel 5.\*.

Installation
------------
This package can be installed via [Composer](http://getcomposer.org) by requiring the ```campo/laravel-shipstation``` package in your project's ```composer.json```
```json
{
    "require": {
        "campo/laravel-shipstation": "~1.0"
    }
}
```

Then at your Laravel project root run:
```sh
composer update
```

Second, add the LaravelShipStation service provider to your providers array located in ```config/app.php```
```php
LaravelShipStation\ShipStationServiceProvider::class
```

After installing via composer you will need to publish the configuration:
```php
php artisan vendor:publish
```
This will create the configuration file for your API key and API secret key at ```config/shipstation.php```. You will need to obtain your API & Secret key from ShipStation: [How can I get access to ShipStation's API?](https://help.shipstation.com/hc/en-us/articles/206638917-How-can-I-get-access-to-ShipStation-s-API-)
## Dependencies
LaravelShipStation uses ```GuzzleHttp\Guzzle```
## Endpoints
Endpoints for the API are accessed via properties (e.g. ```$shipStation->orders->get($options)``` will make a GET request to ```/orders/{$options}```). The default endpoint is /orders/. Valid endpoints include:
* accounts
* carriers
* customers
* orders
* products
* shipments
* stores
* users
* warehouses
* webhooks

## Methods
### GET
```php
$shipStation->{$endpoint}->get($options = [], $endpoint = '');
```
Example of getting an order with the orderNumber of 1.
```php
$shipStation = $this->app['LaravelShipStation\ShipStation'];
$options = ['orderNumber' => 1];
$order = $shipStation->orders->get($options);
````
### POST
```php
$shipStation->{$endpoint}->post($options = [], $endpoint = '');
```
The second parameter ($endpoint) is for any additional endpoints that need to be added. For example, to create an order the POST request would go to /orders/createorder. "createorder" is the additional endpoint since we specify the root endpoint as a property: ```$shipstation->orders->post($options, 'createorders')```

There are models that contain all of the properties available via the API. These models will be converted to arrays when passed to the API.

An example on how to create a new order to be shipped:
```php
    $shipStation = $this->app['LaravelShipStation\ShipStation'];

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
    $item->name = "Awesome sweater.";
    $item->quantity = '1';
    $item->unitPrice  = '29.99';
    $item->warehouseLocation = 'Warehouse A';

    $order = new LaravelShipStation\Models\Order();

    $order->orderNumber = '1';
    $order->orderDate = '2016-05-09';
    $order->orderStatus = 'awaiting_shipment';
    $order->amountPaid = '29.99';
    $order->taxAmount = '0.00';
    $order->shippingAmount = '0.00';
    $order->internalNotes = 'A note about my order.';
    $order->billTo = $address;
    $order->shipTo = $address;
    $order->items[] = $item;

    // This will var_dump the newly created order.
    var_dump($shipStation->orders->post($order, 'createorder'));
    // or with the helper: $shipStation->orders->create($order); would be the same.
```
## DELETE
```php
$shipStation->{$endpoint}->delete($resourceEndPoint);
```
Example of deleting an order by it's order ID:
```php
$shipStation->orders->delete($orderId);
```
## UPDATE
```php
$shipStation->{$endpoint}->update($query = [], $resourceEndPoint);
```
## Simple Wrapper Helpers
Helpers are located in ```/src/Helpers``` and will be named after the endpoint. Currently there is only a helper for the /orders endpoint and /shipments endpint. I will be adding more; feel free to send a PR with any you use.

Check to see if an order already exists in ShipStation via an Order Number:
```php
$orderExists = $shipStation->orders->exists($orderNumber) // returns bool
```
Check how many orders are in ```awaiting_fulfillment``` status:
```php
$count = $shipStation->orders->awaitingShipmentCount(); // returns int
```
Create an order in ShipStation:
```php
$newOrder = $shipStation->orders->create($order);
```
Get the shipments for a specific order number.
```php
$shipments = $shipStation->shipments->forOrder($orderNumber);
```

## ShipStation API Rate Limit
ShipStation only allows for 40 API calls that resets every 60 seconds (or 1 call every 1.5 seconds). By default, LaravelShipStation will protect against any calls being rate limited by pausing when we are averaging more than 1 call every 1.5 seconds.
## Tests
Tests can be ran using ```phpunit```. 
Please note that tests will create an order, check the order, and delete the order in your production environment. By default, tests are disabled. If you would like to run the tests edit the ```phpunit.xml``` file to set the environment variable ```SHIPSTATION_TESTING``` to ```true``` and set your API Key & Secret Key.
## Contribution
Pull requests are most certainly welcomed! This is a WIP.
## License
The MIT License (MIT). Please see [License File](https://github.com/joecampo/larashipstation/blob/master/LICENSE) for more information.
[ico-version]: https://img.shields.io/packagist/v/campo/laravel-shipstation.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/campo/laravel-shipstation
[packagist-downloads]: https://img.shields.io/packagist/dt/campo/laravel-shipstation.svg