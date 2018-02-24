<?php
namespace Yurun\Util\Chinese\Driver\Pinyin;

use Yurun\Util\Chinese\Pinyin;

interface BaseInterface
{
	/**
	 * 把字符串转为拼音结果，返回的数组成员为字符串
	 * @param string $string
	 * @param int $mode
	 * @param string $wordSplit
	 * @return array
	 */
	public function convert($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = null);

}