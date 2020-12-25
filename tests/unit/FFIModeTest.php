<?php
namespace Yurun\Util\ChineseUtil\Test;

/**
 * @testdox FFI Mode
 */
class FFIModeTest extends BaseTest
{
    /**
     * 模式
     *
     * @var string
     */
    protected $mode = 'FFI';

    protected function check()
    {
        if('0' === getenv('CHINESE_UTIL_FFI'))
        {
            $this->markTestSkipped('Not test FFI');
        }
        if(version_compare(PHP_VERSION, '7.4', '<'))
        {
            $this->markTestSkipped('PHP need >= 7.4');
        }
        if(!extension_loaded('FFI'))
        {
            $this->markTestSkipped('You must enable FFI extension');
        }
    }

}
