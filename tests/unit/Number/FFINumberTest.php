<?php
namespace Yurun\Util\ChineseUtil\Test\Number;

/**
 * @testdox FFI Mode 中文数字转换
 */
class FFINumberTest extends BaseNumberTest
{
    /**
     * 模式
     *
     * @var string
     */
    protected $mode = 'FFI';

    protected function check()
    {
        if(version_compare(PHP_VERSION, '<', '7.4'))
        {
            $this->markTestSkipped('PHP need >= 7.4');
        }
        if(!extension_loaded('FFI'))
        {
            $this->markTestSkipped('You must enable FFI extension');
        }
    }

}
