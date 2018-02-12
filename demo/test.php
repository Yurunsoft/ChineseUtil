<?php
namespace Yurun\Util;
require_once dirname(__DIR__) . '/vendor/autoload.php';
use \Yurun\Util\Chinese\Pinyin;
// 信息
$mem1 = memory_get_usage();
$info = Chinese::info();
$mem2 = memory_get_usage();
echo '总共收录 ', $info['chars'], ' 个汉字，', $info['scCount'], ' 个简体字，', $info['tcCount'], ' 个繁体字，', $info['otherCount'], ' 个其它汉字。', PHP_EOL;
echo '加载数据字典前内存占用：', $mem1, '，加载数据字典后内存占用：', $mem2, PHP_EOL;
// 汉字转拼音
$string = '恭喜發財！把我翻译成拼音看下？';
echo $string, PHP_EOL;
echo '所有结果:', PHP_EOL;
var_dump(Chinese::toPinyin($string));
echo '全拼:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN));
echo '首字母:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_FIRST));
echo '读音:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND));
echo '读音数字:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER));
echo '自选 + 自定义分隔符:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN | Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER, '/'));
// 拼音分词
$string2 = 'xianggang';
echo '"', $string2, '"的分词结果：', PHP_EOL;
var_dump(Chinese::splitPinyin($string2));
// 简繁互转
$string3 = '中华人民共和国！恭喜發財！';
echo '"', $string3, '"的简体转换：', PHP_EOL;
var_dump(Chinese::toSimplified($string3));
echo '"', $string3, '"的繁体转换：', PHP_EOL;
var_dump(Chinese::toTraditional($string3));
