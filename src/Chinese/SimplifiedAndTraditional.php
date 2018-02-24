<?php
namespace Yurun\Util\Chinese;

use \Yurun\Util\Chinese;

class SimplifiedAndTraditional
{
	/**
	 * 拼音处理器
	 * @var \Yurun\Util\Chinese\Driver\SimplifiedTraditional\BaseInterface
	 */
	public static $handler;

	/**
	 * 繁体转简体
	 * @param string $string
	 * @return array
	 */
	public static function toSimplified($string)
	{
		return static::getHandler()->toSimplified($string);
	}

	/**
	 * 简体转繁体
	 * @param string $string
	 * @return array
	 */
	public static function toTraditional($string)
	{
		return static::getHandler()->toTraditional($string);
	}

	/**
	 * 获取拼音处理器
	 * @return \Yurun\Util\Chinese\Driver\SimplifiedTraditional\BaseInterface
	 */
	protected static function getHandler()
	{
		if(null === static::$handler)
		{
			$className = '\Yurun\Util\Chinese\Driver\SimplifiedTraditional\\' . Chinese::getMode();
			static::$handler = new $className;
		}
		return static::$handler;
	}
}