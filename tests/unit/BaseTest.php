<?php
namespace Yurun\Util\ChineseUtil\Test;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Pinyin;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * 模式
     *
     * @var string
     */
    protected $mode;

    public function testMode()
    {
        Chinese::setMode($this->mode);
        $this->assertEquals($this->mode, Chinese::getMode());
    }

    /**
     * @testdox pinyin-1
     *
     * @return void
     */
    public function testPinyin1()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123'));
        $this->assertEquals(<<<EXPECTED
array(4) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
  ["pinyinSound"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox pinyin-2
     *
     * @return void
     */
    public function testPinyin2()
    {
        ob_start();
        var_dump(Chinese::toPinyin('我的'));
        $this->assertEquals(<<<EXPECTED
array(4) {
  ["pinyin"]=>
  array(2) {
    [0]=>
    array(2) {
      [0]=>
      string(2) "wo"
      [1]=>
      string(2) "di"
    }
    [1]=>
    array(2) {
      [0]=>
      string(2) "wo"
      [1]=>
      string(2) "de"
    }
  }
  ["pinyinSound"]=>
  array(3) {
    [0]=>
    array(2) {
      [0]=>
      string(3) "wǒ"
      [1]=>
      string(3) "dí"
    }
    [1]=>
    array(2) {
      [0]=>
      string(3) "wǒ"
      [1]=>
      string(3) "dì"
    }
    [2]=>
    array(2) {
      [0]=>
      string(3) "wǒ"
      [1]=>
      string(2) "de"
    }
  }
  ["pinyinSoundNumber"]=>
  array(3) {
    [0]=>
    array(2) {
      [0]=>
      string(3) "wo3"
      [1]=>
      string(3) "di2"
    }
    [1]=>
    array(2) {
      [0]=>
      string(3) "wo3"
      [1]=>
      string(3) "di4"
    }
    [2]=>
    array(2) {
      [0]=>
      string(3) "wo3"
      [1]=>
      string(3) "de0"
    }
  }
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    array(2) {
      [0]=>
      string(1) "w"
      [1]=>
      string(1) "d"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 全拼
     *
     * @return void
     */
    public function testPinyinAll()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 拼音首字母
     *
     * @return void
     */
    public function testPinyinFirst()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_FIRST));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyinFirst"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 读音
     *
     * @return void
     */
    public function testPinyinSound()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_SOUND));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyinSound"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 读音数字
     *
     * @return void
     */
    public function testPinyinSoundNumber()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    array(8) {
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
      [5]=>
      string(1) "1"
      [6]=>
      string(1) "2"
      [7]=>
      string(1) "3"
    }
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 自选返回格式 + 以文本格式返回 + 自定义分隔符
     *
     * @return void
     */
    public function testPinyinCustom()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN | Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER, ' '));
        $this->assertEquals(<<<EXPECTED
array(2) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(24) "gong xi fa cai ！ 1 2 3"
  }
  ["pinyinSoundNumber"]=>
  array(1) {
    [0]=>
    string(28) "gong1 xi3 fa1 cai2 ！ 1 2 3"
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 分割无拼音字符
     *
     * @return void
     */
    public function testPinyinSplitNoPinyin()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN, '-'));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(24) "gong-xi-fa-cai-！-1-2-3"
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 不分割无拼音字符
     *
     * @return void
     */
    public function testPinyinNotSplitNoPinyin()
    {
        ob_start();
        var_dump(Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN, '-', false));
        $this->assertEquals(<<<EXPECTED
array(1) {
  ["pinyin"]=>
  array(1) {
    [0]=>
    string(21) "gong-xi-fa-cai-！123"
  }
}

EXPECTED
        , ob_get_clean());
    }

    /**
     * @testdox 拼音分词
     *
     * @return void
     */
    public function testPinyinSplit()
    {
        $this->assertEquals([
            'xi ang gang ',
            'xiang gang ',
        ], Chinese::splitPinyin('xianggang'));
    }

    /**
     * @testdox 简繁互转
     *
     * @return void
     */
    public function testSimplifiedAndTraditional()
    {
        $simplified = '中华人民共和国！恭喜发财！';
        $traditional = '中華人民共和國！恭喜發財！';
        $this->assertEquals([$traditional, '中華人民共和國！恭喜髮財！'], Chinese::toTraditional($simplified));
        $this->assertEquals([$simplified], Chinese::toSimplified($traditional));
    }

}
