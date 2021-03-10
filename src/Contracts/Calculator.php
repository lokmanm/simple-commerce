<?php

namespace DoubleThreeDigital\SimpleCommerce\Contracts;

use DoubleThreeDigital\SimpleCommerce\Orders\Order;

interface Calculator
{
    public function calculate(Order $order): array;

    public function calculateLineItem(array $data, array $lineItem): array;

    public function calculateLineItemTax(array $data, array $lineItem): array;

    public function calculateOrderShipping(array $data): array;

    public function calculateOrderCoupons(array $data): array;
}
