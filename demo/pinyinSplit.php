<?php
/**
 * 拼音分词示例.
 */

namespace Yurun\Util;

require_once \dirname(__DIR__) . '/vendor/autoload.php';

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

// 拼音分词
$string2 = 'xianggang';
echo '"', $string2, '"的分词结果：', \PHP_EOL;
var_dump(Chinese::splitPinyin($string2));

echo '当前模式:', Chinese::getMode(), \PHP_EOL;
echo '开始内存:', $mem1, '; 结束内存:', memory_get_usage(), '; 峰值内存:', memory_get_peak_usage(), \PHP_EOL;
echo '耗时:', microtime(true) - $time, 's', \PHP_EOL;
