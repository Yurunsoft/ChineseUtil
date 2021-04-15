<?php

namespace Yurun\Util;

use Yurun\Util\Chinese\Pinyin;
use Yurun\Util\Chinese\PinyinSplit;
use Yurun\Util\Chinese\SimplifiedAndTraditional;

class Chinese
{
    /**
     * 是否已初始化.
     *
     * @var bool
     */
    public static $isInited = false;

    /**
     * 配置数据.
     *
     * @var array
     */
    public static $option = [];

    /**
     * 中文数据.
     *
     * @var array
     */
    public static $chineseData = [];

    /**
     * 模式.
     *
     * @var string
     */
    private static $mode;

    /**
     * 初始化.
     *
     * @param array $option 初始化配置
     *
     * @return void
     */
    public static function init($option = [])
    {
        static::$option = $option;
        if (null === static::$mode)
        {
            // 优先使用通用模式，如果环境不支持 PDO 将采用兼容模式。
            if (\extension_loaded('pdo_sqlite'))
            {
                static::setMode('SQLite');
            }
            else
            {
                static::setMode('JSON');
            }
        }
        static::$isInited = true;
    }

    /**
     * 将字符串转换为拼音，非中文原样保留.
     *
     * @param string $string
     * @param int    $mode
     * @param string $wordSplit
     * @param bool   $splitNotPinyinChar 分割无拼音字符。如果为true，如123结果分割为['1','2','3']；如果为false，如123结果分割为['123']
     *
     * @return array
     */
    public static function toPinyin($string, $mode = Pinyin::CONVERT_MODE_FULL, $wordSplit = null, $splitNotPinyinChar = true)
    {
        if (!static::$isInited)
        {
            static::init();
        }

        return Pinyin::toText($string, $mode, $wordSplit, $splitNotPinyinChar);
    }

    /**
     * 拼音分词.
     *
     * @param string $string
     *
     * @return array
     */
    public static function splitPinyin($string, $wordSplit = ' ')
    {
        return PinyinSplit::split($string, $wordSplit);
    }

    /**
     * 拼音分词.
     *
     * @param string $string
     *
     * @return array
     */
    public static function splitPinyinArray($string)
    {
        return PinyinSplit::split($string, null);
    }

    /**
     * 繁体转简体.
     *
     * @param string $string
     *
     * @return array
     */
    public static function toSimplified($string)
    {
        if (!static::$isInited)
        {
            static::init();
        }

        return SimplifiedAndTraditional::toSimplified($string);
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
        if (!static::$isInited)
        {
            static::init();
        }

        return SimplifiedAndTraditional::toTraditional($string);
    }

    /**
     * 设置模式.
     *
     * @param string $mode
     *
     * @return void
     */
    public static function setMode($mode)
    {
        if (static::$mode !== $mode)
        {
            static::$mode = $mode;
            static::$isInited = false;
            static::$chineseData = [];
        }
    }

    /**
     * 获取模式.
     *
     * @return string
     */
    public static function getMode()
    {
        return static::$mode;
    }
}
