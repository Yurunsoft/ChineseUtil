<?php
namespace Yurun\Util\Chinese;

use \Yurun\Util\Chinese;

class Pinyin
{
	/**
	 * 转换为全拼
	 */
	const CONVERT_MODE_PINYIN = 1;

	/**
	 * 转换为带声调读音的拼音
	 */
	const CONVERT_MODE_PINYIN_SOUND = 2;

	/**
	 * 转换为带声调读音的拼音，但声调表示为数字
	 */
	const CONVERT_MODE_PINYIN_SOUND_NUMBER = 4;

	/**
	 * 转换为拼音首字母
	 */
	const CONVERT_MODE_PINYIN_FIRST = 8;

	/**
	 * 转换为上面支持的所有类型
	 */
	const CONVERT_MODE_FULL = 15;

	/**
	 * 拼音处理器
	 * @var \Yurun\Util\Chinese\Driver\Pinyin\BaseInterface
	 */
	public static $handler;

	/**
	 * 把字符串转为拼音结果，返回的数组成员为数组
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public static function convert($string, $mode = Pinyin::CONVERT_MODE_FULL)
	{
		return static::getHandler()->convert($string, $mode);
	}

	/**
	 * 把字符串转为拼音结果，返回的数组成员为字符串
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public static function toText($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = ' ')
	{
		return static::getHandler()->convert($string, $mode, $wordSplit);
	}

	/**
	 * 获取拼音处理器
	 * @return \Yurun\Util\Chinese\Driver\Pinyin\BaseInterface
	 */
	protected static function getHandler()
	{
		if(null === static::$handler)
		{
			$className = '\Yurun\Util\Chinese\Driver\Pinyin\\' . Chinese::getMode();
			static::$handler = new $className;
		}
		return static::$handler;
	}
}