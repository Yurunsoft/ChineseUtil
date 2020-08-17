<?php
namespace Yurun\Util\Chinese;

use \FFI as PHPFFI;

class FFIDriver
{
    /**
     * 处理器
     *
     * @var static
     */
    private static $handler;

    /**
     * .so 文件路径
     *
     * @var string|null
     */
    public static $library;

    /**
     * 字符数据文件路径
     *
     * @var string|null
     */
    public static $characterDataPath;

    /**
     * 拼音数据文件路径
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
        if(!extension_loaded('FFI'))
        {
            throw new \RuntimeException('If you want to use FFI mode, you must use PHP>=7.4 and enable FFI extension');
        }
        $clibPath = dirname(__DIR__, 2) . '/clib';
        if(null === $library)
        {
            $swooleInstalled = defined('SWOOLE_VERSION');
            switch(PHP_OS_FAMILY)
            {
                case 'Darwin':
                    if($swooleInstalled)
                    {
                        $library = 'libchinese_util-swoole4.5.dylib';
                    }
                    else
                    {
                        $library = 'libchinese_util-php7.4.dylib';
                    }
                case 'Windows':
                    return 'Release/chinese_util-php7.4-x' . (4 === PHP_INT_SIZE ? '86' : '64') . '.dll';
                default:
                    if($swooleInstalled)
                    {
                        $library = 'libchinese_util-swoole4.5.so';
                    }
                    else
                    {
                        $library = 'libchinese_util-php7.4.so';
                    }
            }
            $library = $clibPath . '/' . $library;
        }
        $this->ffi = $ffi = PHPFFI::cdef(file_get_contents($clibPath . '/include.h'), $library);
        $ffi->init_chinese_util();
        $dataPath = dirname(__DIR__, 2) . '/data';
        init_chinese_dict($characterDataPath ?? ($dataPath . '/charsData.json'), $pinyinDataPath ?? ($dataPath . '/pinyinData.json'));
    }

    /**
     * 获取拼音处理器
     * @return static
     */
    public static function getHandler()
    {
        if(null === static::$handler)
        {
            static::$handler = new static(static::$library, static::$characterDataPath, static::$pinyinDataPath);
        }
        return static::$handler;
    }

}
