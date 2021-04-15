<?php

namespace Yurun\Util\Chinese;

use Yurun\Util\Chinese;

abstract class Money
{
    /**
     * 处理器.
     *
     * @var \Yurun\Util\Chinese\Driver\Money\BaseInterface
     */
    public static $handler;

    /**
     * 处理器的模式.
     *
     * @var string
     */
    private static $handlerMode = 'Memory';

    /**
     * 中文金额大写转数字.
     *
     * @param string $text
     *
     * @return string
     */
    public static function toNumber($text)
    {
        return static::getHandler()->toNumber($text);
    }

    /**
     * 数字转为中文金额大写.
     *
     * @param string $number
     * @param array  $options
     *
     * @return string
     */
    public static function toChinese($number, $options = [])
    {
        return static::getHandler()->toChinese($number, $options);
    }

    /**
     * 获取处理器.
     *
     * @return \Yurun\Util\Chinese\Driver\Money\BaseInterface
     */
    protected static function getHandler()
    {
        $mode = Chinese::getMode();
        if (null === static::$handler || $mode !== static::$handlerMode)
        {
            if (null === $mode)
            {
                $mode = static::$handlerMode;
            }
            else
            {
                static::$handlerMode = $mode;
            }
            $className = '\Yurun\Util\Chinese\Driver\Money\\' . $mode;
            static::$handler = new $className();
        }

        return static::$handler;
    }
}
