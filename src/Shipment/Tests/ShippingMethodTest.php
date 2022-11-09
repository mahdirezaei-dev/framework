<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethodTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-26
 *
 */

namespace Vanilo\Shipment\Tests;

use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\ShippingMethod;

class ShippingMethodTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $method = ShippingMethod::create(['name' => 'DHL']);

        $this->assertInstanceOf(ShippingMethod::class, $method);
        $this->assertEquals('DHL', $method->name);
    }

    /** @test */
    public function a_carrier_can_be_assigned_to_it()
    {
        $dhl = Carrier::create(['name' => 'DHL']);
        $method = ShippingMethod::create([
            'name' => 'DHL Express',
            'carrier_id' => $dhl->id,
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $method);
        $this->assertEquals($dhl->id, $method->carrier_id);
        $this->assertEquals($dhl->id, $method->carrier->id);
        $this->assertEquals($dhl->name, $method->carrier->name);
    }

    /** @test */
    public function the_configuration_is_an_empty_array_by_default()
    {
        $method = ShippingMethod::create(['name' => 'Pick-up']);

        $this->assertIsArray($method->configuration);
        $this->assertEmpty($method->configuration);
    }

    /** @test */
    public function the_configuration_can_be_set_as_an_array()
    {
        $method = ShippingMethod::create(['name' => 'UPS Ground']);

        $method->configuration = ['price' => 8.99, 'free_threshold' => 25];
        $method->save();
        $method = $method->fresh();

        $this->assertEquals(8.99, $method->configuration['price']);
        $this->assertEquals(25, $method->configuration['free_threshold']);
    }

    /** @test */
    public function it_is_active_by_default()
    {
        $method = ShippingMethod::create(['name' => 'Bike Courier'])->fresh();

        $this->assertTrue($method->is_active);
    }

    /** @test */
    public function it_can_be_set_to_inactive()
    {
        $method = ShippingMethod::create(['name' => 'Dead Messenger', 'is_active' => false])->fresh();

        $this->assertFalse($method->is_active);
    }

    /** @test */
    public function active_ones_can_be_listed()
    {
        ShippingMethod::create(['name' => 'Messenger 1', 'is_active' => true]);
        ShippingMethod::create(['name' => 'Messenger 2', 'is_active' => false]);
        ShippingMethod::create(['name' => 'Messenger 3', 'is_active' => true]);
        ShippingMethod::create(['name' => 'Messenger 4', 'is_active' => false]);
        ShippingMethod::create(['name' => 'Messenger 5', 'is_active' => false]);

        $this->assertCount(2, ShippingMethod::actives()->get());
    }

    /** @test */
    public function inactive_ones_can_be_listed()
    {
        ShippingMethod::create(['name' => 'Messenger 1', 'is_active' => true]);
        ShippingMethod::create(['name' => 'Messenger 2', 'is_active' => false]);
        ShippingMethod::create(['name' => 'Messenger 3', 'is_active' => true]);
        ShippingMethod::create(['name' => 'Messenger 4', 'is_active' => false]);
        ShippingMethod::create(['name' => 'Messenger 5', 'is_active' => false]);

        $this->assertCount(2, ShippingMethod::actives()->get());
    }
}
