<?php

namespace Yurun\Util\Chinese\Traits;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\SQLiteData;

trait MemoryInit
{
    protected function initData()
    {
        SQLiteData::init();
        if (!isset(Chinese::$chineseData['chars']))
        {
            $data = SQLiteData::getAllData();
            $this->parseData($data);
            Chinese::$chineseData['chars'] = $data;
        }
    }

    /**
     * 处理数据.
     *
     * @param array $array
     */
    protected function parseData(&$array)
    {
        $s = \count($array);
        for ($i = 0; $i < $s; ++$i)
        {
            $char = $array[$i]['char'];
            unset($array[$i]['char']);
            $array[$char] = $array[$i];
            unset($array[$i]);
        }
    }
}
