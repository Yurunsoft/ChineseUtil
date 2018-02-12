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
	 * 把字符串转为拼音结果
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public static function convert($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = ' ')
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
	public static function parseResult($list, $mode, $wordSplit = '')
	{
		$isPinyin = (($mode & static::CONVERT_MODE_PINYIN) === static::CONVERT_MODE_PINYIN);
		$isPinyinSound = (($mode & static::CONVERT_MODE_PINYIN_SOUND) === static::CONVERT_MODE_PINYIN_SOUND);
		$isPinyinSoundNumber = (($mode & static::CONVERT_MODE_PINYIN_SOUND_NUMBER) === static::CONVERT_MODE_PINYIN_SOUND_NUMBER);
		$isPinyinFirst = (($mode & static::CONVERT_MODE_PINYIN_FIRST) === static::CONVERT_MODE_PINYIN_FIRST);
		$result = [];
		if($isPinyin)
		{
			$result['pinyin'] = [''];
		}
		if($isPinyinSound)
		{
			$result['pinyinSound'] = [''];
		}
		if($isPinyinSoundNumber)
		{
			$result['pinyinSoundNumber'] = [''];
		}
		if($isPinyinFirst)
		{
			$result['pinyinFirst'] = [''];
		}
		$oldResultCount = null;
		foreach($list as $item)
		{
			// 拼音和拼音首字母
			$count = count($item['pinyin']);
			if($isPinyin || $isPinyinFirst)
			{
				if($isPinyin)
				{
					$oldResultCount = count($result['pinyin']);
					$oldResultPinyin = $result['pinyin'];
				}
				if($isPinyinFirst)
				{
					if(null === $oldResultCount)
					{
						$oldResultCount = count($result['pinyinFirst']);
					}
					$oldResultPinyinFirst = $result['pinyinFirst'];
				}
				for($i = 0; $i < $count - 1; ++$i)
				{
					if($isPinyin)
					{
						$result['pinyin'] = array_merge($result['pinyin'], $oldResultPinyin);
					}
					if($isPinyinFirst)
					{
						$result['pinyinFirst'] = array_merge($result['pinyinFirst'], $oldResultPinyinFirst);
					}
				}
				foreach($item['pinyin'] as $index => $pinyin)
				{
					for($i = 0; $i < $oldResultCount; ++$i)
					{
						$j = $index * $oldResultCount + $i;
						if($isPinyin)
						{
							$result['pinyin'][$j] .= $pinyin . $wordSplit;
						}
						if($isPinyinFirst)
						{
							$result['pinyinFirst'][$j] .= mb_substr($pinyin, 0, 1) . $wordSplit;
						}
					}
				}
			}
			// 拼音读音
			if($isPinyinSound || $isPinyinSoundNumber)
			{
				$oldResultCount = null;
				if(isset($item['pinyinSound']))
				{
					$count = count($item['pinyinSound']);
				}
				else
				{
					$count = 0;
				}
				if($isPinyinSound)
				{
					$oldResultCount = count($result['pinyinSound']);
					$oldResultPinyinSound = $result['pinyinSound'];
				}
				if($isPinyinSoundNumber)
				{
					if(null === $oldResultCount)
					{
						$oldResultCount = count($result['pinyinSoundNumber']);
					}
					$oldResultPinyinSoundNumber = $result['pinyinSoundNumber'];
				}
				for($i = 0; $i < $count - 1; ++$i)
				{
					if($isPinyinSound)
					{
						$result['pinyinSound'] = array_merge($result['pinyinSound'], $oldResultPinyinSound);
					}
					if($isPinyinSoundNumber)
					{
						$result['pinyinSoundNumber'] = array_merge($result['pinyinSoundNumber'], $oldResultPinyinSoundNumber);
					}
				}
				for($index = 0; $index < $count; ++$index)
				{
					for($i = 0; $i < $oldResultCount; ++$i)
					{
						$j = $index * $oldResultCount + $i;
						if($isPinyinSound)
						{
							$result['pinyinSound'][$j] .= $item['pinyinSound'][$index] . $wordSplit;
						}
						if($isPinyinSoundNumber)
						{
							$result['pinyinSoundNumber'][$j] .= $item['pinyinSoundNumber'][$index] . $wordSplit;
						}
					}
				}
			}
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
					'pinyin'	=>	[$word]
				);
			}
		}
		return $list;
	}
}