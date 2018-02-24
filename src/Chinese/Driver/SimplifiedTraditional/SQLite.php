<?php
namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese\SQLiteData;

class SQLite extends Base
{
	public function __construct()
	{
		SQLiteData::init();
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
			$data = SQLiteData::getData($word, $key);
			if(isset($data[$key][0]))
			{
				$list[] = $data[$key];
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