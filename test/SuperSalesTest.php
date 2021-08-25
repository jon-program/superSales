<?php

use PHPUnit\Framework\TestCase;

class SuperSalesTest extends TestCase
{
    public function testSuperSales(): void
    {
        require_once __DIR__ . '/../lib/SuperSales.php';
        $this->assertSame(1298, calc('21:00', [1, 1, 1, 3, 5, 7, 8, 9, 10]));
    }
}
