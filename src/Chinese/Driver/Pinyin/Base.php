<?php
namespace Yurun\Util\Chinese\Driver\Pinyin;

abstract class Base implements BaseInterface
{
	/**
	 * 把字符串转为拼音结果，返回的数组成员为字符串
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public function convert($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = null)
	{
		return $this->parseResult($this->getResult($string), $mode, $wordSplit);
	}

	public function superUnique($array)
	{
		return array_map('unserialize', array_unique(array_map('serialize', $array)));
	}

	/**
	 * 处理结果
	 * @param array $list
	 * @param int $mode
	 * @param string $wordSplit
	 * @return void
	 */
	protected abstract function parseResult($list, $mode, $wordSplit);

	/**
	 * 把字符串转为拼音数组结果
	 * @param string $string
	 * @return array
	 */
	protected abstract function getResult($string);
}