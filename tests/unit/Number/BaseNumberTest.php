<?php
namespace Yurun\Util\ChineseUtil\Test\Number;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Number;
use PHPUnit\Framework\TestCase;

/**
 * @testdox 中文数字转换
 */
abstract class BaseNumberTest extends TestCase
{
    /**
     * 模式
     *
     * @var string
     */
    protected $mode;

    protected function check()
    {

    }

    public function testMode()
    {
        $this->check();
        Chinese::setMode($this->mode);
        $this->assertEquals($this->mode, Chinese::getMode());
    }

    public function testToChinese()
    {
        $this->check();
        // 数字
        $this->assertEquals('五', Number::toChinese(5));
        $this->assertEquals('一十二', Number::toChinese(12));

        // 过滤一十二的一
        $this->assertEquals('五', Number::toChinese(5, [
            'tenMin'    =>  true,
        ]));
        $this->assertEquals('十二', Number::toChinese(12, [
            'tenMin'    =>  true,
        ]));

        // 负数
        $this->assertEquals('负五', Number::toChinese(-5));

        // 小数
        $this->assertEquals('三点一四一五', Number::toChinese(3.1415));

    }

    public function testToNumber()
    {
        $this->check();
        // 数字
        $this->assertEquals(5, Number::toNumber('五'));
        $this->assertEquals(12, Number::toNumber('一十二'));
        $this->assertEquals(12, Number::toNumber('十二'));

        // 负数
        $this->assertEquals(-5, Number::toNumber('负五'));

        // 小数
        $this->assertEquals(3.1415, Number::toNumber('三点一四一五'));
    }

}
