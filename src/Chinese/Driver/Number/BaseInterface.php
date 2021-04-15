<?php

namespace Yurun\Util\Chinese\Driver\Number;

interface BaseInterface
{
    /**
     * 中文口语化数字转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public function toNumber($text);

    /**
     * 数字转为中文口语化数字.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    public function toChinese($number, $options = []);
}
