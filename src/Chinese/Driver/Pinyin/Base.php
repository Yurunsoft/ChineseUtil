<?php

namespace Yurun\Util\Chinese\Driver\Pinyin;

use Yurun\Util\Chinese\Pinyin;

abstract class Base implements BaseInterface
{
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
        return $this->parseResult($this->getResult($string, $splitNotPinyinChar), $mode, $wordSplit);
    }

    /**
     * 结果去重.
     *
     * @param array $array
     *
     * @return array
     */
    protected function uniqueResult($array)
    {
        $newArray = array_map('unserialize', array_unique(array_map('serialize', $array)));
        if ($array !== $newArray)
        {
            $newArray = array_values($newArray);
        }

        return $newArray;
    }

    /**
     * 处理结果.
     *
     * @param array  $list
     * @param int    $mode
     * @param string $wordSplit
     *
     * @return void
     */
    abstract protected function parseResult($list, $mode, $wordSplit);

    /**
     * 把字符串转为拼音数组结果.
     *
     * @param string $string
     * @param bool   $splitNotPinyinChar 分割无拼音字符。如果为true，如123结果分割为['1','2','3']；如果为false，如123结果分割为['123']
     *
     * @return array
     */
    abstract protected function getResult($string, $splitNotPinyinChar = true);
}
