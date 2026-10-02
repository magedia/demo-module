<?php

declare(strict_types=1);

namespace Magedia\DemoSales\Api\Data\Magento;

interface OrderInterface
{
    public const ORDERS_SAMPLE_DATA = [
        [
            'currency_id' => 'USD',
            'email' => 'john_smith@order.com',
            'shipping_address' => [
                'firstname' => 'John',
                'lastname' => 'Smith',
                'street' => 'Green str, 67',
                'city' => 'CityM',
                'country_id' => 'US',
                'region_id' => '1',
                'region' => 'Alabama',
                'postcode' => '46373',
                'telephone' => '3468676',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['sku' => '24-UB02', 'qty' => 1],
                ['sku' => '24-MB05', 'qty' => 1]
            ]
        ],
        [
            'currency_id' => 'USD',
            'email' => 'veronica_costello@order.com',
            'shipping_address' => [
                'firstname' => 'Veronica',
                'lastname' => 'Costello',
                'street' => '6146 Honey Bluff Parkway',
                'city' => 'Calder',
                'country_id' => 'US',
                'region_id' => '5',
                'region' => 'Michigan',
                'postcode' => '49628-7978',
                'telephone' => '77789',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['sku' => '24-MB02', 'qty' => 1],
                ['sku' => '24-WB01', 'qty' => 1],
                ['sku' => '24-MB03', 'qty' => 1],
                ['sku' => '24-WB02', 'qty' => 1]
            ]
        ],
        [
            'currency_id' => 'USD',
            'email' => 'roni_cost@example.com',
            'shipping_address' => [
                'firstname' => 'Roni',
                'lastname' => 'Cost',
                'street' => 'Order street, 12',
                'city' => 'Ordercity',
                'country_id' => 'US',
                'region_id' => '2',
                'region' => 'Alaska',
                'postcode' => '43244',
                'telephone' => '52332',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['sku' => '24-WB04', 'qty' => 1],
                ['sku' => '24-UG01', 'qty' => 1]
            ]
        ],
    ];
}

