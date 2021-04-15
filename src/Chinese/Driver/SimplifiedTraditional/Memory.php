<?php

namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese;

class Memory extends Base
{
    use \Yurun\Util\Chinese\Traits\MemoryInit;

    public function __construct()
    {
        $this->initData();
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
        for ($i = 0; $i < $len; ++$i)
        {
            $word = mb_substr($string, $i, 1, 'UTF-8');
            if (isset(Chinese::$chineseData['chars'][$word][$key]) && [] !== Chinese::$chineseData['chars'][$word][$key])
            {
                $list[] = Chinese::$chineseData['chars'][$word][$key];
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
