# ChineseUtil
PHP 中文工具类，支持汉字转拼音、拼音分词、简繁互转。

PHP Chinese Tool class, support Chinese pinyin, pinyin participle, simplified and traditional conversion

目前本类库拥有的三个功能，都是在实际开发过程中整理出来的。这次使用的数据不同于以前我开源过汉字转拼音和简繁互转，数据都是从字典网站采集下来的，比以前的数据更加准确。

由于中文的博大精深，字有多音字，简体字和繁体字也有多种对应。并且本类库返回的所有结果，均为包含所有组合的数组。

本类库字典数据总共收录 73925 个汉字，包括：3955 个简体字，1761 个繁体字，68209 个其它汉字。

## 内存占用

类库第一个版本发布开始，群里朋友就展开了激烈的讨论，最大的问题就在于内存占用以及性能问题上。经过我不断尝试几种方案，最终决定设置三种模式，来适应不同用户之间的需求。

* 性能模式 (Memory)，使用 SQLite 作为数据载体，一次性加载所有数据到变量，内存占用高(80 MB)，性能最佳。
* 通用模式 (SQLite)，使用 SQLite 作为数据载体，每次查询都通过 SQL 查询，内存占用低(600+ KB)，性能中等。
* 兼容模式 (JSON)，使用精简过的 JSON 数据作为数据载体，一次性加载所有数据到变量，内存占用中(28 MB)，性能差。

> 内存占用量以实际为准，根据版本、扩展等环境的不同，占用的内存容量不一样，上述值为我电脑上的情况，仅供参考。

性能模式适合运行于持久性服务，推荐使用 Swoole 开发服务程序，只加载一次数据，无需重复加载。当然，你服务器内存足够大，或者并发访问不高也可以使用这种模式。

性能模式和通用模式需要 PDO 和 PDO_SQLITE 扩展支持。

兼容模式无扩展依赖，由于精简了数据，一些拼音结果需要经过代码计算处理才可以得出，所以性能较差。

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
$string = '恭喜發財！';
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
/**
所有结果:
array(4) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(4) "gong"
      [1]=>
      string(2) "xi"
      [2]=>
      string(2) "fa"
      [3]=>
      string(3) "cai"
      [4]=>
      string(3) "！"
    }
  }
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(5) "gong1"
      [1]=>
      string(3) "xi3"
      [2]=>
      string(3) "fa1"
      [3]=>
      string(4) "cai2"
      [4]=>
      string(3) "！"
    }
  }
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(1) "g"
      [1]=>
      string(1) "x"
      [2]=>
      string(1) "f"
      [3]=>
      string(1) "c"
      [4]=>
      string(3) "！"
    }
  }
  ["pinyinSound"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(5) "gōng"
      [1]=>
      string(3) "xǐ"
      [2]=>
      string(3) "fā"
      [3]=>
      string(4) "cái"
      [4]=>
      string(3) "！"
    }
  }
}
全拼:
array(1) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(4) "gong"
      [1]=>
      string(2) "xi"
      [2]=>
      string(2) "fa"
      [3]=>
      string(3) "cai"
      [4]=>
      string(3) "！"
    }
  }
}
首字母:
array(1) {
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(1) "g"
      [1]=>
      string(1) "x"
      [2]=>
      string(1) "f"
      [3]=>
      string(1) "c"
      [4]=>
      string(3) "！"
    }
  }
}
读音:
array(1) {
  ["pinyinSound"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(5) "gōng"
      [1]=>
      string(3) "xǐ"
      [2]=>
      string(3) "fā"
      [3]=>
      string(4) "cái"
      [4]=>
      string(3) "！"
    }
  }
}
读音数字:
array(1) {
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    array(5) {
      [0]=>
      string(5) "gong1"
      [1]=>
      string(3) "xi3"
      [2]=>
      string(3) "fa1"
      [3]=>
      string(4) "cai2"
      [4]=>
      string(3) "！"
    }
  }
}
自选返回格式 + 以文本格式返回 + 自定义分隔符:
array(2) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(18) "gong xi fa cai ！"
  }
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    string(22) "gong1 xi3 fa1 cai2 ！"
  }
}
 * /
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