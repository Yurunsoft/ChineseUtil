<?php

namespace Yurun\Util\Chinese\Driver\Pinyin;

use Yurun\Util\Chinese\FFIDriver;
use Yurun\Util\Chinese\Pinyin;

class FFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('FFI');
    }

    /**
     * 把字符串转为拼音结果，返回的数组成员为字符串.
     *
     * @param string $string
     * @param int    $mode
     * @param string $wordSplit
     * @param bool   $splitNotPinyinChar 分割无拼音字符。如果为true，如123结果分割为['1','2','3']；如果为false，如123结果分割为['123']
     *
     * @return array
     */
    public function convert($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = null, $splitNotPinyinChar = true)
    {
        if (null === $wordSplit)
        {
            return convert_to_pinyin_array($string, $mode, $splitNotPinyinChar);
        }
        else
        {
            return convert_to_pinyin_string($string, $mode, $splitNotPinyinChar, $wordSplit);
        }
    }
}
