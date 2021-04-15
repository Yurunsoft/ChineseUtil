<?php

namespace Yurun\Util\Chinese;

use Yurun\Util\Chinese;

class SimplifiedAndTraditional
{
    /**
     * 处理器.
     *
     * @var \Yurun\Util\Chinese\Driver\SimplifiedTraditional\BaseInterface
     */
    public static $handler;

    /**
     * 处理器的模式.
     *
     * @var string
     */
    private static $handlerMode = 'Memory';

    /**
     * 繁体转简体.
     *
     * @param string $string
     *
     * @return array
     */
    public static function toSimplified($string)
    {
        return static::getHandler()->toSimplified($string);
    }

    /**
     * 简体转繁体.
     *
     * @param string $string
     *
     * @return array
     */
    public static function toTraditional($string)
    {
        return static::getHandler()->toTraditional($string);
    }

    /**
     * 获取处理器.
     *
     * @return \Yurun\Util\Chinese\Driver\SimplifiedTraditional\BaseInterface
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
            $className = '\Yurun\Util\Chinese\Driver\SimplifiedTraditional\\' . $mode;
            static::$handler = new $className();
        }

        return static::$handler;
    }
}
