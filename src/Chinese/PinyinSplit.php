<?php
namespace Yurun\Util\Chinese;

use \Yurun\Util\Chinese;

class PinyinSplit
{
	public $stacks = array();
	public $results = array();
	public $itemIndex = 0;
	public $pinyinLength;
	public $lengthPos = array();
	public $pinyins = array();

	public function __construct()
	{
		// 拼音分词数据加载
		if(!isset(Chinese::$option['pinyinSplitData']))
		{
			if(!empty(Chinese::$option['pinyinSplitData']))
			{
				Chinese::$chineseData['pinyin'] = Chinese::$option['pinyinSplitData'];
			}
			else if(empty(Chinese::$option['pinyinSplitDataPath']))
			{
				Chinese::$chineseData['pinyin'] = json_decode(file_get_contents(dirname(dirname(__DIR__)) . '/data/pinyinSplitData.json'), true);
			}
			else
			{
				Chinese::$chineseData['pinyin'] = json_decode(file_get_contents(Chinese::$option['pinyinSplitDataPath']), true);
			}
		}
	}

	public static function split($text)
	{
		$ins = new static;
		return $ins->parse($text);
	}

	public function parse($text)
	{
		$this->results = array();
		$this->pinyins = preg_split('/([^a-zA-Z]+)/', $text, null, PREG_SPLIT_DELIM_CAPTURE);
		foreach($this->pinyins as $index => $pinyin)
		{
			$this->itemIndex = $index;
			$this->results[$index] = array();
			$this->parseItem($pinyin);
		}
		$results = $this->parseResults(0, $result, $firstResult);
		return $results;
	}

	private function parseItem($text)
	{
		$this->stacks = array(array());
		$length = strlen($text);
		$lengthIndex = 0;
		$this->pinyinStr = '';
		$stackLength = 1;
		// 处理成几列数据
		for($i = 0; $i < $length; ++$i)
		{
			if($this->charIsLetter($text[$i]))
			{
				++$lengthIndex;
				$this->pinyinStr .= $text[$i];
			}
			else
			{
				continue;
			}
			for($j = 0; $j < $stackLength; ++$j)
			{
				if(isset($this->stacks[$j]['break']))
				{
					continue;
				}
				$str = (isset($this->stacks[$j][0]) ? $this->stacks[$j][count($this->stacks[$j]) - 1]['pinyin'] : '') . $text[$i];
				$this->checkStack($str, $isIn, $isPinyin);
				if($isIn)
				{
					$this->stacks[$j][] = array('pinyin'=>$str, 'isPinyin'=>$isPinyin);
				}
				else
				{
					$this->stacks[$j]['break'] = true;
				}
			}
			if($i > 0 && $text[$i] !== $this->stacks[$stackLength - 1][0] && in_array($text[$i], Chinese::$chineseData['pinyin']['shengmu']))
			{
				$this->checkStack($text[$i], $isIn, $isPinyin);
				$this->stacks[] = array(array('pinyin'=>$text[$i], 'isPinyin'=>$isPinyin));
				++$stackLength;
			}
			$this->lengthPos[$lengthIndex] = $stackLength;
		}
		$this->pinyinLength = $lengthIndex;
		// 提取所有拼音可能性
		$result = $this->extractPinyins();
	}

	private function extractPinyins($lastStr = '', $lastStrSpace = '', $index = 0)
	{
		$result = '';
		$stackLength = count($this->stacks);
		$bigHasResult = false;
		foreach($this->stacks[$index] as $item)
		{
			if(!$item['isPinyin'])
			{
				continue;
			}
			$result2 = '';
			$str2 = $lastStrSpace . $item['pinyin'] . ' ';
			$str = $lastStr . $item['pinyin'];
			$len = strlen($str);
			$nextChar = isset($this->stacks[$this->lengthPos[$len]][0]['pinyin']) ? $this->stacks[$this->lengthPos[$len]][0]['pinyin'] : null;
			if($nextChar !== (isset($this->pinyinStr[$len]) ? $this->pinyinStr[$len] : ''))
			{
				if($len === $this->pinyinLength)
				{
					$this->results[$this->itemIndex][] = $str2;
					$result .= $item['pinyin'] . ' ';
					$bigHasResult = true;
				}
			}
			else
			{
				$hasResult = false;
				for($i = $index + 1; $i < $stackLength; ++$i)
				{
					if($this->lengthPos[$len] === $i)
					{
						$t = $this->extractPinyins($str, $str2, $i);
						$hasResult = false !== $t;
						$bigHasResult |= $hasResult;
						if(false !== $t)
						{
							$result2 .= $t . ' ';
						}
						break;
					}
				}
				if($hasResult)
				{
					$result .= $item['pinyin'] . ' ' . $result2 . ' ';
				}
				else
				{
				}
			}
		}
		if(!$bigHasResult)
		{
			return false;
		}
		return $result;
	}

	private function parseResults($index = 0, &$result, &$firstResult)
	{
		$result = array();
		if(!isset($this->results[$index][0]))
		{
			$str = $this->splitStr($this->pinyins[$index]);
			if(isset($this->results[$index + 1]))
			{
				$this->parseResults($index + 1, $tresult, $tfirstResult);
				if(isset($tresult[0]))
				{
					foreach($tresult as $item2)
					{
						$result[] = $str . $item2;
					}
				}
				else
				{
					$result[] = $str;
				}
			}
			else
			{
				$result[] = $str;
			}
		}
		else
		{
			foreach($this->results[$index] as $listIndex => $item)
			{
				$firstStr = mb_substr($item, 0, 1);
				if(isset($this->results[$index + 1]))
				{
					$list = $this->parseResults($index + 1, $tresult, $tfirstResult);
					if(isset($tresult[0]))
					{
						foreach($tresult as $item2)
						{
							$result[] = $item . $item2;
						}
					}
					else
					{
						$result[] = $item;
					}
				}
				else
				{
					$result[] = $item;
				}
			}

		}
		return $result;
	}

	private function checkStack($str, &$isIn, &$isPinyin)
	{
		$tmp = &Chinese::$chineseData['pinyin']['relation'];
		$length = strlen($str);
		for($i = 0; $i < $length; ++$i)
		{
			if(isset($tmp[$str[$i]]))
			{
				$tmp = &$tmp[$str[$i]];
			}
			else
			{
				$isIn = $isPinyin = false;
				return;
			}
		}
		$isIn = true;
		$isPinyin = isset($tmp['py']);
	}
	
	private function charIsLetter($char)
	{
		$ascii = ord($char);
		return ($ascii >= 65 && $ascii <= 90) || ($ascii >= 97 && $ascii <= 172);
	}

	private function splitStr($string)
	{
		$len = mb_strlen($string, 'UTF-8');
		$result = '';
		for($i = 0; $i < $len; ++$i)
		{
			$result .= mb_substr($string, $i, 1, 'UTF-8') . ' ';
		}
		return isset($result[1]) ? substr($result, 0, -1) : $result;
	}
}