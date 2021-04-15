<?php

namespace Yurun\Util\Chinese;

use Yurun\Util\Chinese;

class PinyinSplit
{
    /**
     * 拼音分词处理器.
     *
     * @var \Yurun\Util\Chinese\Driver\PinyinSplit\BaseInterface
     */
    public static $handler;

    /**
     * 处理器的模式.
     *
     * @var string
     */
    private static $handlerMode = 'Memory';

    /**
     * 拼音分词.
     *
     * @param string      $text
     * @param string|null $wordSplit
     *
     * @return array
     */
    public static function split($text, $wordSplit = ' ')
    {
        return static::getHandler()->split($text, $wordSplit);
    }

    /**
     * 获取拼音处理器.
     *
     * @return \Yurun\Util\Chinese\Driver\PinyinSplit\BaseInterface
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
            if (\in_array($mode, [
                'JSON',
                'Memory',
                'SQLite',
            ]))
            {
                $className = '\Yurun\Util\Chinese\Driver\PinyinSplit\Memory';
            }
            else
            {
                $className = '\Yurun\Util\Chinese\Driver\PinyinSplit\\' . $mode;
            }
            static::$handler = new $className();
        }

        return static::$handler;
    }
}
