<?php
namespace Yurun\Util\Chinese;

use \Yurun\Util\Chinese;

class SimplifiedAndTraditional
{
	/**
	 * 繁体转简体
	 * @param string $string
	 * @return array
	 */
	public static function toSimplified($string)
	{
		return static::parseResult(static::getResult($string, 'sc'));
	}

	/**
	 * 简体转繁体
	 * @param string $string
	 * @return array
	 */
	public static function toTraditional($string)
	{
		return static::parseResult(static::getResult($string, 'tc'));
	}

	/**
	 * 把字符串转为数组结果
	 * @param string $string
	 * @return array
	 */
	public static function getResult($string, $key)
	{
		$len = mb_strlen($string, 'UTF-8');
		$list = array();
		for($i = 0; $i < $len; ++$i)
		{
			$word = mb_substr($string, $i, 1, 'UTF-8');
			if(isset(Chinese::$chineseData['chars'][$word][$key][0]))
			{
				$list[] = Chinese::$chineseData['chars'][$word][$key];
			}
			else
			{
				$list[] = array(
					$word
				);
			}
		}
		return $list;
	}

	/**
	 * 处理结果
	 * @param array $list
	 * @return void
	 */
	public static function parseResult($list)
	{
		$strings = array('');
		foreach($list as $pinyins)
		{
			$count = count($pinyins);
			$oldResultCount = count($strings);
			$oldResult = $strings;
			for($i=0;$i<$count - 1;++$i)
			{
				$strings = array_merge($strings, $oldResult);
			}
			foreach($pinyins as $index => $pinyin)
			{
				for($i = 0; $i < $oldResultCount; ++$i)
				{
					$j = $index * $oldResultCount + $i;
					$strings[$j] .= $pinyin;
				}
			}
		}
		return $strings;
	}
}