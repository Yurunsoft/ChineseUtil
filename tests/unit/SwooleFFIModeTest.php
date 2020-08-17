<?php
namespace Yurun\Util\ChineseUtil\Test;

/**
 * @testdox Swoole FFI Mode
 */
class SwooleFFIModeTest extends BaseTest
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
