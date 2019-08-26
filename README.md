# ChineseUtil

PHP 中文工具类，支持汉字转拼音、拼音分词、简繁互转。

PHP Chinese Tool class, support Chinese pinyin, pinyin participle, simplified and traditional conversion

目前本类库拥有的三个功能，都是在实际开发过程中整理出来的。这次使用的数据不同于以前我开源过汉字转拼音和简繁互转，数据都是从字典网站采集下来的，比以前的数据更加准确。

由于中文的博大精深，字有多音字，简体字和繁体字也有多种对应。并且本类库返回的所有结果，均为包含所有组合的数组。

本类库字典数据总共收录 73925 个汉字，包括：3955 个简体字，1761 个繁体字，68209 个其它汉字。

## 内存占用

类库第一个版本发布开始，群里朋友就展开了激烈的讨论，最大的问题就在于内存占用以及性能问题上。经过我不断尝试几种方案，最终决定设置三种模式，来适应不同用户之间的需求。

我已经尝试过 Redis，速度比 SQLite 慢 3 倍，没有必要加入支持。所以目前来看，SQLite 模式性价比最高！

---

### 性能模式 (Memory)

使用 SQLite 作为数据载体，一次性加载所有数据到变量，内存占用高(80+ MB)，性能最佳。

适合用于运行 Cli 任务。

需要 PDO 和 PDO_SQLITE 扩展支持。

### 通用模式 (SQLite)

使用 SQLite 作为数据载体，每次查询都通过 SQL 查询，内存占用低(100-200 KB)，性能中等。

适合用于大部分场景。

需要 PDO 和 PDO_SQLITE 扩展支持。

### 兼容模式 (JSON)

使用精简过的 JSON 数据作为数据载体，一次性加载所有数据到变量，内存占用中(30+ MB)，性能差。

> 内存占用量以实际为准，根据版本、扩展等环境的不同，占用的内存容量不一样，上述值为我电脑上的情况，仅供参考。

适合无法使用 PDO 的场景。

由于精简了数据，一些拼音结果需要经过代码计算处理才可以得出，所以性能较差。

---

默认情况下，优先使用通用模式，如果环境不支持 PDO 将采用兼容模式。

你可以在未执行任何初始化或者转换处理之前，设置使用何种模式运行。

```php
// 设为性能模式
Chinese::setMode('Memory');
// 设为通用模式
Chinese::setMode('SQLite');
// 设为兼容模式
Chinese::setMode('JSON');
```

无论何种模式，拼音分词所需数据总是从 JSON 数据中加载。

## 使用说明

### Composer 直接安装

`composer require yurunsoft/chinese-util`

### Composer 项目配置引入

```
"require": {
    "yurunsoft/chinese-util" : "~1.0"
}
```

## 功能

### 汉字转拼音

```php
use \Yurun\Util\Chinese;
use \Yurun\Util\Chinese\Pinyin;
$string = '恭喜發財！123';
echo $string, PHP_EOL;

echo '全拼:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN));

echo '首字母:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_FIRST));

echo '读音:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND));

echo '读音数字:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER));

echo '自选返回格式 + 以文本格式返回 + 自定义分隔符:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN | Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER, ' '));

echo '所有结果:', PHP_EOL;
var_dump(Chinese::toPinyin($string));

echo '不分割无拼音字符:', PHP_EOL;
var_dump(Chinese::toPinyin($string, Pinyin::CONVERT_MODE_PINYIN, ' ', false));

// 结果太长，请自行运行代码查看
```

### 拼音分词

```php
use \Yurun\Util\Chinese;
$string2 = 'xianggang';
echo '"', $string2, '"的分词结果:', PHP_EOL;
var_dump(Chinese::splitPinyin($string2));
/**
输出结果:
"xianggang"的分词结果:
array(2) {
  [0]=>
  string(12) "xi ang gang "
  [1]=>
  string(11) "xiang gang "
}
 * /
```

### 简繁互转

```php
use \Yurun\Util\Chinese;
$string3 = '中华人民共和国！恭喜發財！';
echo '"', $string3, '"的简体转换:', PHP_EOL;
var_dump(Chinese::toSimplified($string3));
echo '"', $string3, '"的繁体转换:', PHP_EOL;
var_dump(Chinese::toTraditional($string3));
/**
输出结果:
"中华人民共和国！恭喜發財！"的简体转换:
array(1) {
  [0]=>
  string(39) "中华人民共和国！恭喜发财！"
}
"中华人民共和国！恭喜發財！"的繁体转换:
array(1) {
  [0]=>
  string(39) "中華人民共和國！恭喜發財！"
}
 * /
```

### 数字转换

```php
use Yurun\Util\Chinese\Number;
function test($number)
{
    $chinese = Number::toChinese($number, [
        'tenMin'    =>  true, // “一十二” => “十二”
    ]);
    $afterNumber = Number::toNumber($chinese);
    echo $number, '=>', $chinese, '=>', $afterNumber, '=>', 0 === bccomp($number, $afterNumber, 20) ? 'true' : 'false', PHP_EOL;
}

test(1.234);
test(-1234567890.666);
test(pi());
/**
输出结果:
1.234=>一点二三四=>1.234=>true
-1234567890.666=>负十二亿三千四百五十六万七千八百九十点六六六=>-1234567890.666=>true
3.1415926535898=>三点一四一五九二六五三五八九八=>3.1415926535898=>true
 */
```

### 金额数字转换

```php
use Yurun\Util\Chinese\Money;
function test($number)
{
    $chinese = Money::toChinese($number, [
        'tenMin'    =>  true, // “一十二” => “十二”
    ]);
    $afterMoney = Money::toNumber($chinese);
    echo $number, '=>', $chinese, '=>', $afterMoney, '=>', 0 === bccomp($number, $afterMoney) ? 'true' : 'false', PHP_EOL;
}

test(1.234);
test(-1234567890.666);
/**
输出结果:
1.234=>壹圆贰角叁分肆厘=>1.234=>true
-1234567890.666=>负壹拾贰亿叁仟肆佰伍拾陆万柒仟捌佰玖拾圆陆角陆分陆厘=>-1234567890.666=>true
 */
```
