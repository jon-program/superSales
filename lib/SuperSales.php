<?php

const TAX = 10;

/**
 * @return array<int, int> $unitSoldNumber
 */
function saleByTime(string $time): array
{
    $price = [
        1 => 100,
        2 => 150,
        3 => 200,
        4 => 350,
        5 => 180,
        6 => 220,
        7 => 440,
        8 => 380,
        9 => 80,
        10 => 100,
    ];

    // タイムセール割引
    $hourNumber = explode(':', $time);
    if ($hourNumber[0] >= 20 && $hourNumber[0] <= 22) {
        $price[7] /= 2;
        $price[8] /= 2;
    }

    return $price;
}

/**
 * @param array<int, int> $unitSoldNumber
 */
function saleBySoldNumber(array $unitSoldNumber, int $nonTaxedPrice): int
{
    // 玉ねぎ割引
    if ($unitSoldNumber[1] == 3 || $unitSoldNumber[1] == 4) {
        $nonTaxedPrice -= 50;
    } elseif ($unitSoldNumber[1] >= 5) {
        $nonTaxedPrice -= 100;
    }

    // 弁当飲み物割引
    $bentoSoldNumber = $unitSoldNumber[7] + $unitSoldNumber[8];
    $drinkSoldNumber = $unitSoldNumber[5] + $unitSoldNumber[9];
    if ($bentoSoldNumber >= 1 && $drinkSoldNumber >= 1) {
        $nonTaxedPrice -= 20 * min($bentoSoldNumber, $drinkSoldNumber);
    }

    return $nonTaxedPrice;
}

/**
 * @param array<int> $itemNumbers
 * @return array<int, int> $unitSoldNumber
 */
function countSoldNumber(array $itemNumbers): array
{
    $unitSoldNumber = array_count_values($itemNumbers);

    // 買わなかった商品に0を与える(後述のコードで存在しない場合のエラー防止)
    for ($itemNumber = 1; $itemNumber <= 10; $itemNumber++) {
        if (array_key_exists((string) $itemNumber, $unitSoldNumber) == false) {
            $unitSoldNumber += [$itemNumber => 0];
        }
    }

    return $unitSoldNumber;
}

/**
 * @param array<int> $itemNumbers
 */
function calc(string $time, array $itemNumbers): int
{
    $price = saleByTime($time);

    $nonTaxedPrice = 0;
    foreach ($itemNumbers as $itemNumber) {
        $nonTaxedPrice += $price[$itemNumber];
    }

    $unitSoldNumber = countSoldNumber($itemNumbers);

    $nonTaxedPrice = saleBySoldNumber($unitSoldNumber, $nonTaxedPrice);

    return $nonTaxedPrice * (100 + TAX) / 100;
}
