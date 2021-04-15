<?php

namespace Yurun\Util\Chinese\Driver\Number;

use Yurun\Util\Chinese\FFIDriver;

class SwooleFFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('SwooleFFI');
    }

    /**
     * 中文口语化数字转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public function toNumber($text)
    {
        return swoole_convert_chinese_to_number($text);
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
        return swoole_convert_number_to_chinese($number, isset($options['tenMin']) ? $options['tenMin'] : false);
    }
}
