<?php

use DoubleThreeDigital\SimpleCommerce\Facades\Order;
use DoubleThreeDigital\SimpleCommerce\Facades\Product;
use Illuminate\Support\Facades\Session;
use Statamic\Facades\Parse;
use Statamic\Facades\Stache;
use Statamic\Facades\User;

uses(\DoubleThreeDigital\SimpleCommerce\Tests\TestCase::class)->in('Actions', 'Console', 'Coupons', 'Customers', 'Data', 'Fieldtypes', '__fixtures__', 'Gateways', 'Helpers', 'Http', 'Listeners', 'Modifiers', 'Orders', 'Rules', 'Tags', 'Tax');
uses(\DoubleThreeDigital\SimpleCommerce\Tests\Helpers\SetupCollections::class)->in('Actions', 'Coupons', 'Listeners');

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

/** @link https://pestphp.com/docs/configuring-tests */

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/** @link https://pestphp.com/docs/custom-expectations */

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/** @link https://pestphp.com/docs/custom-helpers */
function user()
{
    return User::make()
        ->makeSuper()
        ->email('joe.bloggs@example.com')
        ->set('password', 'secret')
        ->save();
}

function buildCartWithProducts()
{
    $product = Product::make()
        ->price(1000)
        ->data([
            'title' => 'Food',
        ]);

    $product->save();

    $order = Order::make()
        ->lineItems([
            [
                'id' => Stache::generateId(),
                'product' => $product->id,
                'quantity' => 1,
                'total' => 1000,
            ],
        ]);

    $order->save();

    return [$product, $order];
}

function tag($tag)
{
    return Parse::template($tag, []);
}

function fakeCart($cart = null)
{
    if (is_null($cart)) {
        $cart = Order::make()->merge([
            'note' => 'Special note.',
        ]);

        $cart->save();
    }

    Session::shouldReceive('get')
        ->with('simple-commerce-cart')
        ->andReturn($cart->id);

    Session::shouldReceive('token')
        ->andReturn('random-token');

    Session::shouldReceive('has')
        ->with('simple-commerce-cart')
        ->andReturn(true);

    Session::shouldReceive('has')
        ->with('errors')
        ->andReturn([]);

    return $cart;
}
