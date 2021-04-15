<?php

namespace Yurun\Util\Chinese\Driver\Money;

interface BaseInterface
{
    /**
     * 中文金额大写转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public function toNumber($text);

    /**
     * 数字转为中文金额大写.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    public function toChinese($number, $options = []);
}
