<?php
namespace Yurun\Util;

use \Yurun\Util\Chinese\Pinyin;
use \Yurun\Util\Chinese\PinyinSplit;

class Chinese
{
	/**
	 * 是否已初始化
	 * @var boolean
	 */
	public static $isInited = false;

	/**
	 * 中文数据
	 * @var array
	 */
	public static $chineseData;

	/**
	 * 初始化
	 * @param array $option 初始化配置
	 * @return void
	 */
	public static function init($option = null)
	{
		if(!empty($option['chineseData']))
		{
			static::$chineseData = $option['chineseData'];
		}
		else if(empty($option['dataPath']))
		{
			static::$chineseData = json_decode(file_get_contents(dirname(__DIR__) . '/data/chineseData.json'), true);
		}
		else
		{
			static::$chineseData = json_decode(file_get_contents($option['dataPath']), true);
		}
		static::$isInited = true;
	}

	/**
	 * 将字符串转换为拼音，非中文原样保留
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public static function toPinyin($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = ' ')
	{
		if(!static::$isInited)
		{
			static::init();
		}
		return Pinyin::convert($string, $mode);
	}

	/**
	 * 繁体转简体
	 * @param string $string
	 * @return array
	 */
	public static function toSimplified($string)
	{
		if(!static::$isInited)
		{
			static::init();
		}
	}

	/**
	 * 简体转繁体
	 * @param string $string
	 * @return array
	 */
	public static function toTraditional($string)
	{
		if(!static::$isInited)
		{
			static::init();
		}
	}

	/**
	 * 返回中文数据信息
	 * @return array
	 */
	public static function info()
	{
		if(!static::$isInited)
		{
			static::init();
		}
		$result = [];
		$result['chars'] = count(static::$chineseData['chars']);
		$scCount = 0;
		$tcCount = 0;
		$otherCount = 0;
		foreach(static::$chineseData['chars'] as $item)
		{
			if($item['isSC'] === $item['isTC'])
			{
				++$otherCount;
			}
			else if($item['isSC'])
			{
				++$scCount;
			}
			else
			{
				++$tcCount;
			}
		}
		$result['scCount'] = $scCount;
		$result['tcCount'] = $tcCount;
		$result['otherCount'] = $otherCount;
		return $result;
	}
}