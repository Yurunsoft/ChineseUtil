<?php
namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

abstract class Base implements BaseInterface
{
	/**
	 * 繁体转简体
	 * @param string $string
	 * @return array
	 */
	public function toSimplified($string)
	{
		return $this->parseResult($this->getResult($string, 'sc'));
	}

	/**
	 * 简体转繁体
	 * @param string $string
	 * @return array
	 */
	public function toTraditional($string)
	{
		return $this->parseResult($this->getResult($string, 'tc'));
	}

	/**
	 * 处理结果
	 * @param array $list
	 * @return void
	 */
	protected function parseResult($list)
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

	/**
	 * 把字符串转为数组结果
	 * @param string $string
	 * @return array
	 */
	protected abstract function getResult($string, $key);
}