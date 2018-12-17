<?php
/**
 * 中文数字转换示例
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Yurun\Util\Chinese\Number;

function test($number)
{
    $chinese = Number::toChinese($number, [
        'tenMin'    =>  true, // “一十二” => “十二”
    ]);
    $afterNumber = Number::toNumber($chinese);
    echo $number, '=>', $chinese, '=>', $afterNumber, '=>', 0 === bccomp($number, $afterNumber, 20) ? 'true' : 'false', PHP_EOL;
}

/**
 * 随机生成文本
 * @param string $chars
 * @param int $min
 * @param int $max
 * @return string
 */
function text($chars, $min, $max)
{
    $length = mt_rand($min, $max);
    $charLength = mb_strlen($chars);
    $result = '';
    for($i = 0; $i < $length; ++$i)
    {
        $result .= mb_substr($chars, mt_rand(1, $charLength) - 1, 1);
    }
    return $result;
}

/**
 * 随机生成数字
 * @param int $min
 * @param int $max
 * @return string
 */
function digital($min, $max)
{
    return text('0123456789', $min, $max);
}
$count = 10;
echo '整数：', PHP_EOL;
for($i = 1; $i <= $count; ++$i)
{
    do
    {
        $number = ltrim(digital(1, 14), '0');
    } while('' == $number);
    test($number * [1, -1][mt_rand(0, 1)]);
}
echo PHP_EOL;
echo '小数：', PHP_EOL;
for($i = 1; $i <= $count; ++$i)
{
    do
    {
        $number = ltrim(digital(1, 14), '0');
    } while('' == $number);
    $number .= '.' . digital(1, 4);
    test($number * [1, -1][mt_rand(0, 1)]);
}
echo PHP_EOL;