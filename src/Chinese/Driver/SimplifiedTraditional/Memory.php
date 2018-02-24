<?php
namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Pinyin;
use Yurun\Util\Chinese\SQLiteData;

class Memory extends Base
{
	use \Yurun\Util\Chinese\Traits\MemoryInit;

	public function __construct()
	{
		$this->initData();
	}

	/**
	 * 把字符串转为数组结果
	 * @param string $string
	 * @return array
	 */
	protected function getResult($string, $key)
	{
		$len = mb_strlen($string, 'UTF-8');
		$list = array();
		for($i = 0; $i < $len; ++$i)
		{
			$word = mb_substr($string, $i, 1, 'UTF-8');
			if(isset(Chinese::$chineseData['chars'][$key][0]))
			{
				$list[] = Chinese::$chineseData['chars'][$key];
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
}