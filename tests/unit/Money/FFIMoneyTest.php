<?php
namespace Yurun\Util\ChineseUtil\Test\Money;

/**
 * @testdox FFI Mode 中文金额转换
 */
class FFIMoneyTest extends BaseMoneyTest
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
