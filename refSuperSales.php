<?php

const FIRST_ONION_SALE_PRICE = 50;
const FIRST_ONION_SALE_NUMBER = 3;
const SECOND_ONION_SALE_PRICE = 100;
const SECOND_ONION_SALE_NUMBER = 5;
const SET_SALE_PRICE = 20;
const SALE_START_TIME = '20:00';

const TAX = 10;
const PRICES = [
    1 => ['price' => 100, 'type' => ''],
    2 => ['price' => 150, 'type' => ''],
    3 => ['price' => 200, 'type' => ''],
    4 => ['price' => 350, 'type' => ''],
    5 => ['price' => 180, 'type' => 'drink'],
    6 => ['price' => 220, 'type' => ''],
    7 => ['price' => 440, 'type' => 'bento'],
    8 => ['price' => 380, 'type' => 'bento'],
    9 => ['price' => 80, 'type' => 'drink'],
    10 => ['price' => 100, 'type' => 'drink']
];

function calc(string $time, array $itemNumbers): int
{
    $totalAmount = 0;
    $bentoAmount = 0;
    $drink = 0;
    $bento = 0;

    foreach($itemNumbers as $itemNumber) {
        $totalAmount += PRICES[$itemNumber]['price'];

        if (PRICES[$itemNumber]['type'] === 'drink') {
            $drink++;
        }

        if (PRICES[$itemNumber]['type'] === 'bento') {
            $bento++;
            $bentoAmount += PRICES[$itemNumber]['price'];
        }
    }

    $totalAmount -= discountOnion($itemNumbers);
    $totalAmount -= discountSet($drink, $bento);
    $totalAmount -= discountTime($time, $bentoAmount);

    return (int) $totalAmount * (100 + TAX) / 100;
}

function discountOnion(array $itemNumbers): int
{
    $onion = array_count_values($itemNumbers)[1];
    $discount = 0;

    if ($onion >= SECOND_ONION_SALE_NUMBER) {
        $discount = SECOND_ONION_SALE_PRICE;
    } elseif ($onion >= FIRST_ONION_SALE_NUMBER) {
        $discount = FIRST_ONION_SALE_PRICE;
    }

    return $discount;
}

function discountSet(int $drink, int $bento): int
{
    $discountSet = 0;
    $set = min($drink, $bento);

    if ($set >= 1) {
        $discountSet = $set * SET_SALE_PRICE;
    }

    return $discountSet;
}

function discountTime(string $time, int $bentoAmount): int
{
    if (strtotime($time) < strtotime(SALE_START_TIME) ) {
        return 0;
    }

    return (int) $bentoAmount / 2;
}
