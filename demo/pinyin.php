<?php
/**
 * 汉字转拼音示例.
 */

namespace Yurun\Util;

require_once \dirname(__DIR__) . '/vendor/autoload.php';
use Yurun\Util\Chinese\Pinyin;

$time = microtime(true);
$mem1 = memory_get_usage();

// 设为性能模式
// Chinese::setMode('Memory');
// 性能模式占用内存大，如果提示内存不足，请扩大内存限制
// ini_set('memory_limit','256M');

// 设为通用模式，支持 PDO_SQLITE 的情况下为默认
// Chinese::setMode('SQLite');

// 设为兼容模式，不支持 PDO_SQLITE 的情况下为默认
// Chinese::setMode('JSON');

// 汉字转拼音
$string = '恭喜發財！把我翻译成拼音看下？123';
echo $string, \PHP_EOL;
echo '所有结果:', \PHP_EOL;
var_dump(Chinese::toPinyin($string));
echo '全拼:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN));
echo '首字母:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_FIRST));
echo '读音:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND));
echo '读音数字:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER));
echo '自选返回格式 + 以文本格式返回 + 自定义分隔符:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN | Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER, ' '));
echo '不分割无拼音字符:', \PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN, ' ', false));

echo '当前模式:', Chinese::getMode(), \PHP_EOL;
echo '开始内存:', $mem1, '; 结束内存:', memory_get_usage(), '; 峰值内存:', memory_get_peak_usage(), \PHP_EOL;
echo '耗时:', microtime(true) - $time, 's', \PHP_EOL;
