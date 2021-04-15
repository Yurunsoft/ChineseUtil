<?php

namespace Yurun\Util\Chinese\Driver\PinyinSplit;

use Yurun\Util\Chinese\FFIDriver;

class SwooleFFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('SwooleFFI');
    }

    /**
     * 拼音分词.
     *
     * @param string      $text
     * @param string|null $wordSplit
     *
     * @return array
     */
    public function split($text, $wordSplit = ' ')
    {
        if (null === $wordSplit)
        {
            return swoole_split_pinyin_array($text);
        }
        else
        {
            return swoole_split_pinyin_string($text, $wordSplit);
        }
    }
}
