<?php

namespace Yurun\Util\Chinese\Driver\PinyinSplit;

interface BaseInterface
{
    /**
     * 拼音分词.
     *
     * @param string      $text
     * @param string|null $wordSplit
     *
     * @return array
     */
    public function split($text, $wordSplit = ' ');
}
