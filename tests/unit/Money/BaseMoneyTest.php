<?php
namespace Yurun\Util\ChineseUtil\Test\Money;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Money;
use PHPUnit\Framework\TestCase;

/**
 * @testdox 中文金额转换
 */
abstract class BaseMoneyTest extends TestCase
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
        $this->assertEquals('伍圆', Money::toChinese(5));
        $this->assertEquals('壹拾贰圆', Money::toChinese(12));

        // 负数
        $this->assertEquals('负伍圆', Money::toChinese(-5));

        // 小数
        $this->assertEquals('叁圆壹角肆分壹厘伍毫', Money::toChinese(3.1415));

    }

    public function testToNumber()
    {
        $this->check();
        // 数字
        $this->assertEquals(5, Money::toNumber('伍圆'));
        $this->assertEquals(12, Money::toNumber('壹拾贰圆'));

        // 负数
        $this->assertEquals(-5, Money::toNumber('负伍圆'));

        // 小数
        $this->assertEquals(3.1415, Money::toNumber('叁圆壹角肆分壹厘伍毫'));
    }

}
