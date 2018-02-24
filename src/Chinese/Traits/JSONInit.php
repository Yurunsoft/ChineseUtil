<?php
namespace Yurun\Util\Chinese\Traits;

use Yurun\Util\Chinese;

trait JSONInit
{
	protected function loadChars()
	{
		if(!isset(Chinese::$chineseData['chars']))
		{
			if(!empty($option['charsData']))
			{
				Chinese::$chineseData['chars'] = $option['charsData'];
			}
			else if(empty($option['charsDataPath']))
			{
				Chinese::$chineseData['chars'] = json_decode(file_get_contents(dirname(dirname(dirname(__DIR__))) . '/data/charsData.json'), true);
			}
			else
			{
				Chinese::$chineseData['chars'] = json_decode(file_get_contents($option['charsDataPath']), true);
			}
		}
	}

	protected function loadPinyinSound()
	{
		if(!isset(Chinese::$chineseData['pinyinSound']))
		{
			if(!empty($option['pinyinSoundData']))
			{
				Chinese::$chineseData['pinyinSound'] = $option['pinyinSoundData'];
			}
			else if(empty($option['pinyinSoundDataPath']))
			{
				Chinese::$chineseData['pinyinSound'] = json_decode(file_get_contents(dirname(dirname(dirname(__DIR__))) . '/data/pinyinSound.json'), true);
			}
			else
			{
				Chinese::$chineseData['pinyinSound'] = json_decode(file_get_contents($option['pinyinSoundDataPath']), true);
			}
		}
	}
}