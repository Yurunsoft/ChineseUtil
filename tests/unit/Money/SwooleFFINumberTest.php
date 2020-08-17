<?php
namespace Yurun\Util\ChineseUtil\Test\Money;

/**
 * @testdox SwooleFFI Mode 中文金额转换
 */
class SwooleFFIMoneyTest extends BaseMoneyTest
{
    /**
     * 模式
     *
     * @var string
     */
    protected $mode = 'SwooleFFI';

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
        if(!extension_loaded('Swoole'))
        {
            $this->markTestSkipped('You must enable Swoole extension');
        }
    }

}
