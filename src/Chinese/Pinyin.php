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
	 * 把字符串转为拼音结果，返回的数组成员为数组
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public static function convert($string, $mode = Pinyin::CONVERT_MODE_FULL)
	{
		return self::parseResult(self::getResult($string), $mode, null);
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
		return self::parseResult(self::getResult($string), $mode, $wordSplit);
	}

	/**
	 * 处理结果
	 * @param array $list
	 * @param int $mode
	 * @param string $wordSplit
	 * @return void
	 */
	public static function parseResult($list, $mode, $wordSplit)
	{
		$pinyinSounds = [[]];
		$oldResultCount = null;
		foreach($list as $item)
		{
			$item[Chinese::INDEX_PINYIN] = explode(',', $item[Chinese::INDEX_PINYIN]);
			// 拼音和拼音首字母
			$count = count($item[Chinese::INDEX_PINYIN]);
			$oldResultCount = count($pinyinSounds);
			$oldResultPinyin = $pinyinSounds;
			for($i = 0; $i < $count - 1; ++$i)
			{
				$pinyinSounds = array_merge($pinyinSounds, $oldResultPinyin);
			}
			foreach($item[Chinese::INDEX_PINYIN] as $index => $pinyin)
			{
				for($i = 0; $i < $oldResultCount; ++$i)
				{
					$j = $index * $oldResultCount + $i;
					// $pinyinSounds[$j] .= $pinyin . $wordSplit;
					$pinyinSounds[$j][] = $pinyin;
				}
			}
		}

		$isPinyin = (($mode & static::CONVERT_MODE_PINYIN) === static::CONVERT_MODE_PINYIN);
		$isPinyinSoundNumber = (($mode & static::CONVERT_MODE_PINYIN_SOUND_NUMBER) === static::CONVERT_MODE_PINYIN_SOUND_NUMBER);
		$isPinyinFirst = (($mode & static::CONVERT_MODE_PINYIN_FIRST) === static::CONVERT_MODE_PINYIN_FIRST);
		$result = [];
		if($isPinyin)
		{
			$result['pinyin'] = [];
		}
		if($isPinyinSoundNumber)
		{
			$result['pinyinSoundNumber'] = [];
		}
		if($isPinyinFirst)
		{
			$result['pinyinFirst'] = [];
		}

		if((($mode & static::CONVERT_MODE_PINYIN_SOUND) === static::CONVERT_MODE_PINYIN_SOUND))
		{
			if(null === $wordSplit)
			{
				$result['pinyinSound'] = $pinyinSounds;
			}
			else
			{
				foreach($pinyinSounds as $pinyinSoundItem)
				{
					$result['pinyinSound'][] = implode($wordSplit, $pinyinSoundItem);
				}
			}
		}

		foreach($pinyinSounds as $pinyinSound)
		{
			$itemResult = static::parseSoundItem($pinyinSound, $mode);
			if($isPinyin)
			{
				$result['pinyin'][] = null === $wordSplit ? $itemResult['pinyin'] : implode($wordSplit, $itemResult['pinyin']);
			}
			if($isPinyinSoundNumber)
			{
				$result['pinyinSoundNumber'][] = null === $wordSplit ? $itemResult['pinyinSoundNumber'] : implode($wordSplit, $itemResult['pinyinSoundNumber']);
			}
			if($isPinyinFirst)
			{
				$result['pinyinFirst'][] = null === $wordSplit ? $itemResult['pinyinFirst'] : implode($wordSplit, $itemResult['pinyinFirst']);
			}
		}
		
		if($isPinyin)
		{
			$result['pinyin'] = static::superUnique($result['pinyin']);
		}
		if($isPinyinSoundNumber)
		{
			$result['pinyinSoundNumber'] = static::superUnique($result['pinyinSoundNumber']);
		}
		if($isPinyinFirst)
		{
			$result['pinyinFirst'] = static::superUnique($result['pinyinFirst']);
		}

		return $result;
	}

	/**
	 * 把字符串转为拼音数组结果
	 * @param string $string
	 * @return array
	 */
	public static function getResult($string)
	{
		$len = mb_strlen($string, 'UTF-8');
		$list = array();
		for($i = 0; $i < $len; ++$i)
		{
			$word = mb_substr($string, $i, 1, 'UTF-8');
			if(isset(Chinese::$chineseData['chars'][$word]))
			{
				$list[] = Chinese::$chineseData['chars'][$word];
			}
			else
			{
				$list[] = array(
					Chinese::INDEX_PINYIN	=>	$word
				);
			}
		}
		return $list;
	}

	protected static function parseSoundItem($array, $mode)
	{
		static $pattern;
		if(null === $pattern)
		{
			$pattern = '/([' . implode('', array_keys(Chinese::$chineseData['pinyin']['sound'])) . '])/u';
		}
		$isPinyin = (($mode & static::CONVERT_MODE_PINYIN) === static::CONVERT_MODE_PINYIN);
		$isPinyinSoundNumber = (($mode & static::CONVERT_MODE_PINYIN_SOUND_NUMBER) === static::CONVERT_MODE_PINYIN_SOUND_NUMBER);
		$isPinyinFirst = (($mode & static::CONVERT_MODE_PINYIN_FIRST) === static::CONVERT_MODE_PINYIN_FIRST);

		$result = [];

		if($isPinyin)
		{
			$result['pinyin'] = [];
		}
		if($isPinyinSoundNumber)
		{
			$result['pinyinSoundNumber'] = [];
		}
		if($isPinyinFirst)
		{
			$result['pinyinFirst'] = [];
		}

		foreach($array as $pinyinSoundItem)
		{
			if($isPinyin)
			{
				$pinyin = preg_replace_callback(
					$pattern,
					function ($matches){
						return Chinese::$chineseData['pinyin']['sound'][$matches[0]]['ab'];
					},
					$pinyinSoundItem,
					1
				);
				$result['pinyin'][] = $pinyin;
			}
			if($isPinyinSoundNumber)
			{
				$tone = null;
				$str = preg_replace_callback(
					$pattern,
					function ($matches) use(&$tone){
						$tone = Chinese::$chineseData['pinyin']['sound'][$matches[0]]['tone'];
						return Chinese::$chineseData['pinyin']['sound'][$matches[0]]['ab'];
					},
					$pinyinSoundItem,
					1
				);
				if(null === $tone)
				{
					$result['pinyinSoundNumber'][] = $str;
				}
				else
				{
					$result['pinyinSoundNumber'][] = $str . $tone;
				}
			}
			if($isPinyinFirst)
			{
				if(isset($pinyin))
				{
					$result['pinyinFirst'][] = mb_substr($pinyin, 0, 1);
				}
				else
				{
					$result['pinyinFirst'][] = mb_substr(preg_replace_callback(
						$pattern,
						function ($matches){
							return Chinese::$chineseData['pinyin']['sound'][$matches[0]]['ab'];
						},
						$pinyinSoundItem,
						1
					), 0, 1);
				}
			}
		}
		
		return $result;
	}

	public static function superUnique($array)
	{
		return array_map('unserialize', array_unique(array_map('serialize', $array)));
	}
}