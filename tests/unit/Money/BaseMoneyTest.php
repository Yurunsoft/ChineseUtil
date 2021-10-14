<?php

namespace Yurun\Util\ChineseUtil\Test\Money;

use PHPUnit\Framework\TestCase;
use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Money;

/**
 * @testdox 中文金额转换
 */
abstract class BaseMoneyTest extends TestCase
{
    /**
     * 模式.
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
        $this->assertEquals('伍圆', Money::toChinese(5));
        $this->assertEquals('壹拾贰圆', Money::toChinese(12));
        $this->assertEquals('壹佰贰拾叁圆', Money::toChinese(123));
        $this->assertEquals('壹仟贰佰叁拾肆圆', Money::toChinese(1234));
        $this->assertEquals('壹万贰仟叁佰肆拾伍圆', Money::toChinese(12345));

        // 负数
        $this->assertEquals('负伍圆', Money::toChinese(-5));

        // 小数
        $this->assertEquals('叁圆壹角肆分壹厘伍毫', Money::toChinese(3.1415));
        $this->assertEquals('壹角肆分壹厘伍毫', Money::toChinese(0.1415));
    }

    public function testToNumber()
    {
        $this->check();
        // 数字
        $this->assertEquals(5, Money::toNumber('伍圆'));
        $this->assertEquals(5, Money::toNumber('伍元'));
        $this->assertEquals(12, Money::toNumber('壹拾贰圆'));
        $this->assertEquals(12, Money::toNumber('壹拾贰元'));

        // 负数
        $this->assertEquals(-5, Money::toNumber('负伍圆'));
        $this->assertEquals(-5, Money::toNumber('负伍元'));

        // 小数
        $this->assertEquals(3.1415, Money::toNumber('叁圆壹角肆分壹厘伍毫'));
        $this->assertEquals(3.1415, Money::toNumber('叁元壹角肆分壹厘伍毫'));
        $this->assertEquals(0.1415, Money::toNumber('壹角肆分壹厘伍毫'));
    }

    public function testIssue8()
    {
        $this->check();
        $this->assertEquals('零圆', Money::toChinese(0));
        $this->assertEquals('零圆', Money::toChinese('0'));
        $this->assertEquals('零圆', Money::toChinese('0.0'));
    }

    public function testIssue9()
    {
        $this->check();
        $this->assertEquals('壹拾贰圆', Money::toChinese('12.0'));
        $this->assertEquals('壹拾贰圆', Money::toChinese('12.00'));
    }

    public function testIssue15()
    {
        $this->check();
        $this->assertEquals('叁拾圆', Money::toChinese('30'));
        $this->assertEquals('叁拾圆', Money::toChinese('30.0'));
        $this->assertEquals('叁拾圆', Money::toChinese('30.00'));

        $this->assertEquals('叁佰圆', Money::toChinese('300'));
        $this->assertEquals('叁佰圆', Money::toChinese('300.0'));
        $this->assertEquals('叁佰圆', Money::toChinese('300.00'));

        $this->assertEquals('叁仟圆', Money::toChinese('3000'));
        $this->assertEquals('叁仟圆', Money::toChinese('3000.0'));
        $this->assertEquals('叁仟圆', Money::toChinese('3000.00'));

        $this->assertEquals('叁万圆', Money::toChinese('30000'));
        $this->assertEquals('叁万圆', Money::toChinese('30000.0'));
        $this->assertEquals('叁万圆', Money::toChinese('30000.00'));
    }
}
