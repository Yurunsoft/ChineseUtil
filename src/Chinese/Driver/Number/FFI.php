<?php

namespace Yurun\Util\Chinese\Driver\Number;

use Yurun\Util\Chinese\FFIDriver;

class FFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('FFI');
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
        return convert_chinese_to_number($text);
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
        return convert_number_to_chinese($number, isset($options['tenMin']) ? $options['tenMin'] : false);
    }
}
