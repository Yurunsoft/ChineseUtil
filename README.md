# ChineseUtil
PHP 中文工具类，支持汉字转拼音、拼音分词、简繁互转。

PHP Chinese Tool class, support Chinese pinyin, pinyin participle, simplified and traditional conversion

目前本类库拥有的三个功能，都是在实际开发过程中整理出来的。这次使用的数据不同于以前我开源过汉字转拼音和简繁互转，数据都是从字典网站采集下来的，比以前的数据更加准确。

由于中文的博大精深，字有多音字，简体字和繁体字也有多种对应。并且本类库返回的所有结果，均为包含所有组合的数组。

本类库字典数据总共收录 41578 个汉字，包括：3919 个简体字，1734 个繁体字，35925 个其它汉字。

加载后会占用 16 MB 内存，在访问量大的接口要使用此类汉字转拼音、繁简转换功能时，推荐用 Swoole 开发一个异步服务程序，只需加载一次数据，就可以持续高效地为你提供服务。

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

/**
输出结果：
array(4) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(58) "gong xi fa cai ！ ba wo fan yi cheng pin yin kan xia ？ "
  }
  ["pinyinSound"]=>
  array(4) {
    [0]=>
    string(63) "gōng xǐ fā cái bǎ wǒ fān yì chéng pīn yīn kàn xià "
    [1]=>
    string(63) "gōng xǐ fā cái bà wǒ fān yì chéng pīn yīn kàn xià "
    [2]=>
    string(63) "gōng xǐ fā cái bǎ wǒ fān yì chéng pīn yīn kān xià "
    [3]=>
    string(63) "gōng xǐ fā cái bà wǒ fān yì chéng pīn yīn kān xià "
  }
  ["pinyinSoundNumber"]=>
  array(4) {
    [0]=>
    string(63) "gong1 xi3 fa1 cai2 ba3 wo3 fan1 yi4 cheng2 pin1 yin1 kan4 xia4 "
    [1]=>
    string(63) "gong1 xi3 fa1 cai2 ba4 wo3 fan1 yi4 cheng2 pin1 yin1 kan4 xia4 "
    [2]=>
    string(63) "gong1 xi3 fa1 cai2 ba3 wo3 fan1 yi4 cheng2 pin1 yin1 kan1 xia4 "
    [3]=>
    string(63) "gong1 xi3 fa1 cai2 ba4 wo3 fan1 yi4 cheng2 pin1 yin1 kan1 xia4 "
  }
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    string(34) "g x f c ！ b w f y c p y k x ？ "
  }
}
全拼:
array(1) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(58) "gong xi fa cai ！ ba wo fan yi cheng pin yin kan xia ？ "
  }
}
首字母:
array(1) {
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    string(34) "g x f c ！ b w f y c p y k x ？ "
  }
}
读音:
array(1) {
  ["pinyinSound"]=>
  array(4) {
    [0]=>
    string(63) "gōng xǐ fā cái bǎ wǒ fān yì chéng pīn yīn kàn xià "
    [1]=>
    string(63) "gōng xǐ fā cái bà wǒ fān yì chéng pīn yīn kàn xià "
    [2]=>
    string(63) "gōng xǐ fā cái bǎ wǒ fān yì chéng pīn yīn kān xià "
    [3]=>
    string(63) "gōng xǐ fā cái bà wǒ fān yì chéng pīn yīn kān xià "
  }
}
读音数字:
array(1) {
  ["pinyinSoundNumber"]=>
  array(4) {
    [0]=>
    string(63) "gong1 xi3 fa1 cai2 ba3 wo3 fan1 yi4 cheng2 pin1 yin1 kan4 xia4 "
    [1]=>
    string(63) "gong1 xi3 fa1 cai2 ba4 wo3 fan1 yi4 cheng2 pin1 yin1 kan4 xia4 "
    [2]=>
    string(63) "gong1 xi3 fa1 cai2 ba3 wo3 fan1 yi4 cheng2 pin1 yin1 kan1 xia4 "
    [3]=>
    string(63) "gong1 xi3 fa1 cai2 ba4 wo3 fan1 yi4 cheng2 pin1 yin1 kan1 xia4 "
  }
}
自选 + 自定义分隔符:
array(2) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(58) "gong/xi/fa/cai/！/ba/wo/fan/yi/cheng/pin/yin/kan/xia/？/"
  }
  ["pinyinSoundNumber"]=>
  array(4) {
    [0]=>
    string(63) "gong1/xi3/fa1/cai2/ba3/wo3/fan1/yi4/cheng2/pin1/yin1/kan4/xia4/"
    [1]=>
    string(63) "gong1/xi3/fa1/cai2/ba4/wo3/fan1/yi4/cheng2/pin1/yin1/kan4/xia4/"
    [2]=>
    string(63) "gong1/xi3/fa1/cai2/ba3/wo3/fan1/yi4/cheng2/pin1/yin1/kan1/xia4/"
    [3]=>
    string(63) "gong1/xi3/fa1/cai2/ba4/wo3/fan1/yi4/cheng2/pin1/yin1/kan1/xia4/"
  }
}
 * /
```

### 拼音分词

```php
use \Yurun\Util\Chinese;
$string2 = 'xianggang';
echo '"', $string2, '"的分词结果：', PHP_EOL;
var_dump(Chinese::splitPinyin($string2));
/**
输出结果：
"xianggang"的分词结果：
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
echo '"', $string3, '"的简体转换：', PHP_EOL;
var_dump(Chinese::toSimplified($string3));
echo '"', $string3, '"的繁体转换：', PHP_EOL;
var_dump(Chinese::toTraditional($string3));
/**
输出结果：
"中华人民共和国！恭喜發財！"的简体转换：
array(1) {
  [0]=>
  string(39) "中华人民共和国！恭喜发财！"
}
"中华人民共和国！恭喜發財！"的繁体转换：
array(1) {
  [0]=>
  string(39) "中華人民共和國！恭喜發財！"
}
 * /
```