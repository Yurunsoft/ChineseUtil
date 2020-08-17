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

    protected function check()
    {

    }

    public function testMode()
    {
        $this->check();
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
        $this->check();
        $this->assertEquals(array (
          'pinyin' =>
          array (
            array (
              'gong',
              'xi',
              'fa',
              'cai',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
          'pinyinSound' =>
          array (
            array (
              'gōng',
              'xǐ',
              'fā',
              'cái',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
          'pinyinSoundNumber' =>
          array (
            array (
              'gong1',
              'xi3',
              'fa1',
              'cai2',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
          'pinyinFirst' =>
          array (
            array (
              'g',
              'x',
              'f',
              'c',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
        ), Chinese::toPinyin('恭喜發財！123'));
    }

    /**
     * @testdox pinyin-2
     *
     * @return void
     */
    public function testPinyin2()
    {
        $this->check();
        $this->assertEquals(array (
          'pinyin' =>
          array (
            array (
              'wo',
              'di',
            ),
            array (
              'wo',
              'de',
            ),
          ),
          'pinyinSound' =>
          array (
            array (
              'wǒ',
              'dí',
            ),
            array (
              'wǒ',
              'dì',
            ),
            array (
              'wǒ',
              'de',
            ),
          ),
          'pinyinSoundNumber' =>
          array (
            array (
              'wo3',
              'di2',
            ),
            array (
              'wo3',
              'di4',
            ),
            array (
              'wo3',
              'de0',
            ),
          ),
          'pinyinFirst' =>
          array (
            array (
              'w',
              'd',
            ),
          ),
        ), Chinese::toPinyin('我的'));
    }

    /**
     * @testdox 全拼
     *
     * @return void
     */
    public function testPinyinAll()
    {
        $this->check();
        $this->assertEquals(array (
          'pinyin' =>
          array (
            array (
              'gong',
              'xi',
              'fa',
              'cai',
              '！',
              '1',
              '2',
              '3',
            ),
          )), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN));
    }

    /**
     * @testdox 拼音首字母
     *
     * @return void
     */
    public function testPinyinFirst()
    {
        $this->check();
        $this->assertEquals(array(
          'pinyinFirst' =>
          array (
            array (
              'g',
              'x',
              'f',
              'c',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
        ), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_FIRST));
    }

    /**
     * @testdox 读音
     *
     * @return void
     */
    public function testPinyinSound()
    {
        $this->check();
        $this->assertEquals(array(
          'pinyinSound' =>
          array (
            array (
              'gōng',
              'xǐ',
              'fā',
              'cái',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
        ), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_SOUND));
    }

    /**
     * @testdox 读音数字
     *
     * @return void
     */
    public function testPinyinSoundNumber()
    {
        $this->check();
        $this->assertEquals(array(
          'pinyinSoundNumber' =>
          array (
            array (
              'gong1',
              'xi3',
              'fa1',
              'cai2',
              '！',
              '1',
              '2',
              '3',
            ),
          ),
        ), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER));
    }

    /**
     * @testdox 自选返回格式 + 以文本格式返回 + 自定义分隔符
     *
     * @return void
     */
    public function testPinyinCustom()
    {
        $this->check();
        $this->assertEquals(array (
          'pinyin' =>
          array (
            'gong xi fa cai ！ 1 2 3',
          ),
          'pinyinSoundNumber' =>
          array (
            'gong1 xi3 fa1 cai2 ！ 1 2 3',
          )), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN | Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER, ' '));
    }

    /**
     * @testdox 分割无拼音字符
     *
     * @return void
     */
    public function testPinyinSplitNoPinyin()
    {
        $this->check();
        $this->assertEquals(array(
          'pinyin'  =>  array(
            'gong-xi-fa-cai-！-1-2-3',
          ),
        ), Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN, '-'));
    }

    /**
     * @testdox 不分割无拼音字符
     *
     * @return void
     */
    public function testPinyinNotSplitNoPinyin()
    {
        $this->check();
        $this->assertEquals(array(
          'pinyin'  =>  array(
            'gong-xi-fa-cai-！123',
          ),
        ) , Chinese::toPinyin('恭喜發財！123', Pinyin::CONVERT_MODE_PINYIN, '-', false));
    }

    /**
     * @testdox 拼音分词
     *
     * @return void
     */
    public function testPinyinSplit()
    {
        $this->check();
        $this->assertEquals([
            ['xiang', 'gang'],
            ['xi', 'ang', 'gang'],
        ], Chinese::splitPinyinArray('xianggang'));

        $this->assertEquals([
            'xiang gang',
            'xi ang gang',
        ], Chinese::splitPinyin('xianggang'));

        $this->assertEquals([
            's b te lang pu s b',
        ], Chinese::splitPinyin('sbtelangpusb'));
    
        $this->assertEquals([
            '啊 xian',
            '啊 xi an',
        ], Chinese::splitPinyin('啊xian'));
    
        $this->assertEquals([
            'xi 啊 an',
        ], Chinese::splitPinyin('xi啊an'));
    
        $this->assertEquals([
            'xian 啊',
            'xi an 啊',
        ], Chinese::splitPinyin('xian啊'));
    
        $this->assertEquals([
            '一 xian 二',
            '一 xi an 二',
        ], Chinese::splitPinyin('一xian二'));
    
        $this->assertEquals([
            '一 xi 二 an 三',
        ], Chinese::splitPinyin('一xi二an三'));
    }

    /**
     * @testdox 简繁互转
     *
     * @return void
     */
    public function testSimplifiedAndTraditional()
    {
        $this->check();
        $simplified = '中华人民共和国！恭喜发财！';
        $traditional = '中華人民共和國！恭喜發財！';
        $this->assertEquals([$traditional, '中華人民共和國！恭喜髮財！'], Chinese::toTraditional($simplified));
        $this->assertEquals([$simplified], Chinese::toSimplified($traditional));
    }

}
