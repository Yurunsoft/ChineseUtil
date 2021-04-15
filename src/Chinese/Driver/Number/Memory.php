<?php

namespace Yurun\Util\Chinese\Driver\Number;

class Memory implements BaseInterface
{
    public static $numberMap = [
        0   => '零',
        1   => '一',
        2   => '二',
        3   => '三',
        4   => '四',
        5   => '五',
        6   => '六',
        7   => '七',
        8   => '八',
        9   => '九',
        '-' => '负',
        '.' => '点',
    ];

    public static $unitMap = [
        '十',
        '百',
        '千',
        '万',
        '亿',
        '兆',
        '京',
    ];

    /**
     * 中文口语化数字转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public function toNumber($text)
    {
        $length = mb_strlen($text);
        $number = $partNumber = 0;
        $pom = 1; // 正数或负数，1或-1
        $lastNum = 0;
        $isDecimal = false;
        $decimal = '';
        for ($i = 0; $i < $length; ++$i)
        {
            $char = mb_substr($text, $i, 1);
            if (0 === $i && static::$numberMap['-'] === $char)
            {
                $pom = -1;
                continue;
            }
            if (static::$numberMap['.'] === $char)
            {
                $isDecimal = true;
                continue;
            }
            $key = array_search($char, static::$numberMap);
            if (false === $key)
            {
                $key = array_search($char, static::$unitMap);
                if (false === $key)
                {
                    throw new \InvalidArgumentException(sprintf('%s is not a valied chinese number text', $text));
                }

                if (0 === $key && 0 === $lastNum)
                {
                    $lastNum = 1;
                }

                // 单位
                if ($key >= 3)
                {
                    $partNumber += $lastNum;
                    $number += $partNumber * bcpow(10, (($key - 3) * 4) + 4);
                    $partNumber = 0;
                }
                else
                {
                    $partNumber += $lastNum * bcpow(10, $key + 1);
                }

                $lastNum = 0;
            }
            else
            {
                // 数字
                if ($isDecimal)
                {
                    $decimal .= $key;
                }
                else
                {
                    $lastNum = $key;
                }
            }
        }

        return bcmul(bcadd($number, bcadd($partNumber, $lastNum)), $pom) . ($isDecimal ? ('.' . $decimal) : '');
    }

    /**
     * 数字转为中文口语化数字.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    public function toChinese($number, $options = [])
    {
        if (!static::verifyNumber($number))
        {
            throw new \InvalidArgumentException(sprintf('%s is not a valied number', $number));
        }

        list($integer, $decimal) = explode('.', $number . '.');

        if ($integer < 0)
        {
            $pom = static::$numberMap['-'];
            $integer = abs($integer);
        }
        else
        {
            $pom = '';
        }
        $integerPart = static::parseInteger($integer, $options);
        if ('' === $integerPart)
        {
            $integerPart = static::$numberMap[0];
        }
        $decimalPart = static::parseDecimal($decimal, $options);

        return $pom . $integerPart . $decimalPart;
    }

    /**
     * 验证数值
     *
     * @param string $number
     *
     * @return bool
     */
    public static function verifyNumber($number)
    {
        return preg_match('/^-?\d+(\.\d+)?$/', $number) > 0;
    }

    /**
     * 处理整数部分.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    private static function parseInteger($number, $options)
    {
        // “一十二” => “十二”
        $tenMin = isset($options['tenMin']) ? $options['tenMin'] : false;

        // 准备数据，分割为4个数字一组
        $length = \strlen($number);
        // 同 % 4
        $firstItems = $length & 3;
        $leftStr = substr($number, $firstItems);
        if ('' === $leftStr || false === $leftStr)
        {
            $split4 = [];
        }
        else
        {
            $split4 = str_split($leftStr, 4);
        }
        if ($firstItems > 0)
        {
            array_unshift($split4, substr($number, 0, $firstItems));
        }
        $split4Count = \count($split4);

        $unitIndex = ($length - 1) / 4 >> 0;
        if (0 === $unitIndex)
        {
            $unitIndex = -1;
        }
        else
        {
            $unitIndex += 2;
        }

        $result = '';
        foreach ($split4 as $i => $item)
        {
            $index = $unitIndex - $i;

            $length = \strlen($item);

            $itemResult = '';
            $has0 = false;
            for ($j = 0; $j < $length; ++$j)
            {
                if (0 == $item[$j])
                {
                    $has0 = true;
                }
                else
                {
                    if ($has0)
                    {
                        $itemResult .= static::$numberMap[0];
                        $has0 = false;
                    }
                    if (!($tenMin && 2 === $length && 0 === $j && 1 == $item[$j]))
                    {
                        $itemResult .= static::$numberMap[$item[$j]];
                    }
                    if (0 != $item[$j])
                    {
                        $itemResult .= (isset(static::$unitMap[$length - $j - 2]) ? static::$unitMap[$length - $j - 2] : '');
                    }
                }
            }
            if ('' != $itemResult)
            {
                $result .= $itemResult . (($i != $split4Count - 1 && isset(static::$unitMap[$index])) ? static::$unitMap[$index] : '');
            }
        }

        return $result;
    }

    /**
     * 处理小数部分.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    private static function parseDecimal($number, $options)
    {
        if ('' === $number)
        {
            return '';
        }
        $result = static::$numberMap['.'];
        $length = \strlen($number);
        for ($i = 0; $i < $length; ++$i)
        {
            $result .= static::$numberMap[$number[$i]];
        }

        return $result;
    }
}
