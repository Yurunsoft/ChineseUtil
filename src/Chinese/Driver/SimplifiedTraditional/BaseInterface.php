<?php
namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

interface BaseInterface
{
	/**
	 * 繁体转简体
	 * @param string $string
	 * @return array
	 */
	public function toSimplified($string);

	
	/**
	 * 简体转繁体
	 * @param string $string
	 * @return array
	 */
	public function toTraditional($string);
}