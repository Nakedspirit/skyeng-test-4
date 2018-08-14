<?php
/**
 * @param string $a
 * @param string $b
 * @return string
 */
function bigSum(string $a, string $b) : string
{
    if ($a === '0') {
        return $b;
    }
    if ($b === '0') {
        return $a;
    }
    $abs = function(string $val) : string
    {
        if ($val{0} === '-') {
            $val = substr_replace($val, '', 0, 1);
        }
        return $val;
    };
    $comparePositive = function(string $a, string $b, int $aLen, int $bLen) : int
    {
        if ($a === $b) {
            return 0;
        }
        if ($aLen > $bLen) {
            return 1;
        } elseif ($aLen < $bLen) {
            return -1;
        }
        for ($i = 0; $i < $aLen; $i++) {
            $ai = (int) $a[$i];
            $bi = (int) $b[$i];
            if ($ai > $bi) {
                return 1;
            }
            if ($ai < $bi) {
                return -1;
            }
        }
        return 0;
    };
    $process = function (bool $modeSum, string $a, string $b, int $aLen, int $bLen) {
        $perenos = 0;
        $result  = '';
        $maxLen  = max($aLen, $bLen);
        for ($i = 1; $i <= $maxLen; $i++) {
            $iA = $aLen - $i;
            $iB = $bLen - $i;
            $aa = $iA >= 0 && isset($a[$iA]) ? (int)$a[$iA] : 0;
            $bb = $iB >= 0 && isset($b[$iB]) ? (int)$b[$iB] : 0;
            if ($modeSum) {
                $sum = $aa + $bb + $perenos;
                $perenos = $sum >= 10 ? 1 : 0;
                $result .= $sum % 10;
            } else {
                $s = 10 + $aa - $bb - $perenos;
                $perenos = $s < 10 ? 1 : 0;
                $result .= $s % 10;
            }
        }
        if ($modeSum) {
            if ($perenos !== 0) {
                $result .= $perenos;
            }
        } else {
            $result = rtrim($result, '0');
        }
        return strrev($result);
    };
    //получаем значение по модулу
    $aAbs = $abs($a);
    $bAbs = $abs($b);
    //проверяем на отрицательность
    $aIsNeg = $a !== $aAbs;
    $bIsNeg = $b !== $bAbs;
    //считаем длину
    $aLen = strlen($aAbs);
    $bLen = strlen($bAbs);
    if ($aIsNeg xor $bIsNeg) { //разные по знаку
        $cmp = $comparePositive($aAbs, $bAbs, $aLen, $bLen);
        if ($cmp === 0) {
            return '0'; //разные по знаку, но равные по модулу в сумме 0
        } elseif ($cmp === -1) { //$absB > $absA
            $result    = $process(false, $bAbs, $aAbs, $bLen, $aLen);
            $needMinus = !$aIsNeg;
        } else { //$absA > $absB
            $result    = $process(false, $aAbs, $bAbs, $aLen, $bLen);
            $needMinus = !$bIsNeg;
        }
    } else {
        $needMinus = $aIsNeg && $bIsNeg;
        $result    = $process(true, $aAbs, $bAbs, $aLen, $bLen);
    }
    if ($needMinus) {
        $result = '-' . $result;
    }
    return $result;
}