<?php
namespace Yurun\Util\ChineseUtil\Test;

use Yurun\Util\Chinese\Money;
use PHPUnit\Framework\TestCase;

/**
 * @testdox 中文金额转换
 */
class MoneyTest extends TestCase
{
    public function testToChinese()
    {
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
        // 数字
        $this->assertEquals(5, Money::toNumber('伍圆'));
        $this->assertEquals(12, Money::toNumber('壹拾贰圆'));

        // 负数
        $this->assertEquals(-5, Money::toNumber('负伍圆'));

        // 小数
        $this->assertEquals(3.1415, Money::toNumber('叁圆壹角肆分壹厘伍毫'));
    }

}
