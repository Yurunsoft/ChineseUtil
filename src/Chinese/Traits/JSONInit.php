<?php
namespace Yurun\Util\Chinese\Traits;

use Yurun\Util\Chinese;

trait JSONInit
{
    protected function loadChars()
    {
        if(!isset(Chinese::$chineseData['chars']))
        {
            if(!empty(Chinese::$option['charsData']))
            {
                Chinese::$chineseData['chars'] = Chinese::$option['charsData'];
            }
            else if(empty(Chinese::$option['charsDataPath']))
            {
                Chinese::$chineseData['chars'] = json_decode(file_get_contents(dirname(dirname(dirname(__DIR__))) . '/data/charsData.json'), true);
            }
            else
            {
                Chinese::$chineseData['chars'] = json_decode(file_get_contents(Chinese::$option['charsDataPath']), true);
            }
        }
    }

    protected function loadPinyinSound()
    {
        if(!isset(Chinese::$chineseData['pinyinSound']))
        {
            if(!empty(Chinese::$option['pinyinSoundData']))
            {
                Chinese::$chineseData['pinyinSound'] = Chinese::$option['pinyinSoundData'];
            }
            else if(empty(Chinese::$option['pinyinSoundDataPath']))
            {
                Chinese::$chineseData['pinyinSound'] = json_decode(file_get_contents(dirname(dirname(dirname(__DIR__))) . '/data/pinyinSound.json'), true);
            }
            else
            {
                Chinese::$chineseData['pinyinSound'] = json_decode(file_get_contents(Chinese::$option['pinyinSoundDataPath']), true);
            }
        }
    }
}