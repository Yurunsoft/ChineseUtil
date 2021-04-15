<?php

namespace Yurun\Util\Chinese;

use FFI as PHPFFI;

class FFIDriver
{
    /**
     * 处理器集合.
     *
     * @var static[]
     */
    private static $handlers;

    /**
     * .so 文件路径.
     *
     * @var string|null
     */
    public static $library;

    /**
     * 字符数据文件路径.
     *
     * @var string|null
     */
    public static $characterDataPath;

    /**
     * 拼音数据文件路径.
     *
     * @var string|null
     */
    public static $pinyinDataPath;

    /**
     * FFI 对象
     *
     * @var \FFI
     */
    public $ffi;

    public function __construct($library = null, $characterDataPath = null, $pinyinDataPath = null)
    {
        if (!\extension_loaded('FFI'))
        {
            throw new \RuntimeException('If you want to use FFI mode, you must use PHP>=7.4 and enable FFI extension');
        }
        $clibPath = \dirname(__DIR__, 2) . '/clib';
        if (null === $library)
        {
            $swooleInstalled = \defined('SWOOLE_VERSION');
            $phpVersion = \PHP_MAJOR_VERSION . '.' . \PHP_MINOR_VERSION;
            switch (\PHP_OS_FAMILY)
            {
                case 'Darwin':
                    if ($swooleInstalled)
                    {
                        $library = "libchinese_util-php{$phpVersion}-swoole4.5.dylib";
                    }
                    else
                    {
                        $library = "libchinese_util-php{$phpVersion}.dylib";
                    }
                    break;
                case 'Windows':
                    $library = "chinese_util-php{$phpVersion}-x" . (4 === \PHP_INT_SIZE ? '86' : '64') . '.dll';
                    break;
                default:
                    if ($swooleInstalled)
                    {
                        $library = "libchinese_util-php{$phpVersion}-swoole4.5.so";
                    }
                    else
                    {
                        $library = "libchinese_util-php{$phpVersion}.so";
                    }
            }
            $library = $clibPath . '/' . $library;
        }
        $this->ffi = $ffi = PHPFFI::cdef(file_get_contents($clibPath . '/include.h'), $library);
        $ffi->init_chinese_util();
        $dataPath = \dirname(__DIR__, 2) . '/data';
        if (!$characterDataPath)
        {
            $characterDataPath = $dataPath . '/charsData.json';
        }
        if (!$pinyinDataPath)
        {
            $pinyinDataPath = $dataPath . '/pinyinData.json';
        }
        init_chinese_dict($characterDataPath, $pinyinDataPath);
    }

    /**
     * 获取拼音处理器.
     *
     * @return static
     */
    public static function getHandler(string $type)
    {
        if (!isset(static::$handlers[$type]))
        {
            static::$handlers[$type] = new static(static::$library, static::$characterDataPath, static::$pinyinDataPath);
        }

        return static::$handlers[$type];
    }
}
