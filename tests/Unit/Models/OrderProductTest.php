<?php

namespace Tests\Unit\Models;

use App\Models\OrderProduct;
use Tests\TestCase;

class OrderProductTest extends TestCase
{
    private OrderProduct $orderProduct;

    public function setUp(): void
    {
        $this->orderProduct = new OrderProduct();
    }

    /** @dataProvider dataproviderGrossValue */
    public function testGrossValue(int $expectedGrossValue, int $grossPrice, int $quantity): void
    {
        $this->orderProduct->setGrossPrice($grossPrice);
        $this->orderProduct->setQuantity($quantity);

        $this->assertEquals($expectedGrossValue, $this->orderProduct->getGrossValueAttribute());
    }

    public function dataproviderGrossValue(): array
    {
        return [
            'Normal values 1.' => [
                //expectedGrossValue,
                62859,
                //grossPrice
                69,
                //quantity
                911
            ],
            'Normal values 2.' => [
                //expectedGrossValue,
                435,
                //grossPrice
                87,
                //quantity
                5
            ],
        ];
    }
}
