<?php

namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese;

class JSON extends Base
{
    use \Yurun\Util\Chinese\Traits\JSONInit;

    public function __construct()
    {
        $this->loadChars();
    }

    /**
     * 把字符串转为数组结果.
     *
     * @param string $string
     *
     * @return array
     */
    protected function getResult($string, $key)
    {
        $len = mb_strlen($string, 'UTF-8');
        $list = [];
        $index = \constant('\Yurun\Util\Chinese\JSONIndex::INDEX_' . strtoupper($key));
        for ($i = 0; $i < $len; ++$i)
        {
            $word = mb_substr($string, $i, 1, 'UTF-8');
            if (isset(Chinese::$chineseData['chars'][$word][$index]) && '' !== Chinese::$chineseData['chars'][$word][$index])
            {
                $list[] = explode(',', Chinese::$chineseData['chars'][$word][$index]);
            }
            else
            {
                $list[] = [
                    $word,
                ];
            }
        }

        return $list;
    }
}
