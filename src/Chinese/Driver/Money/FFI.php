<?php

namespace Yurun\Util\Chinese\Driver\Money;

use Yurun\Util\Chinese\FFIDriver;

class FFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('FFI');
    }

    /**
     * 中文金额大写转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public function toNumber($text)
    {
        return convert_chinese_to_money($text);
    }

    /**
     * 数字转为中文金额大写.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    public function toChinese($number, $options = [])
    {
        return convert_money_to_chinese($number);
    }
}
