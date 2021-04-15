<?php

namespace Yurun\Util\Chinese\Driver\Pinyin;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\JSONIndex;
use Yurun\Util\Chinese\Pinyin;

class JSON extends Base
{
    use \Yurun\Util\Chinese\Traits\JSONInit;

    public function __construct()
    {
        $this->loadChars();
        $this->loadPinyinSound();
    }

    /**
     * 处理结果.
     *
     * @param array  $list
     * @param int    $mode
     * @param string $wordSplit
     *
     * @return void
     */
    protected function parseResult($list, $mode, $wordSplit)
    {
        $pinyinSounds = [[]];
        $oldResultCount = null;
        foreach ($list as $item)
        {
            $item[JSONIndex::INDEX_PINYIN] = explode(',', $item[JSONIndex::INDEX_PINYIN]);
            // 拼音和拼音首字母
            $count = \count($item[JSONIndex::INDEX_PINYIN]);
            $oldResultCount = \count($pinyinSounds);
            $oldResultPinyin = $pinyinSounds;
            for ($i = 0; $i < $count - 1; ++$i)
            {
                $pinyinSounds = array_merge($pinyinSounds, $oldResultPinyin);
            }
            foreach ($item[JSONIndex::INDEX_PINYIN] as $index => $pinyin)
            {
                for ($i = 0; $i < $oldResultCount; ++$i)
                {
                    $j = $index * $oldResultCount + $i;
                    $pinyinSounds[$j][] = $pinyin;
                }
            }
        }

        $isPinyin = (($mode & Pinyin::CONVERT_MODE_PINYIN) === Pinyin::CONVERT_MODE_PINYIN);
        $isPinyinSound = (($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND) === Pinyin::CONVERT_MODE_PINYIN_SOUND);
        $isPinyinSoundNumber = (($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER) === Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER);
        $isPinyinFirst = (($mode & Pinyin::CONVERT_MODE_PINYIN_FIRST) === Pinyin::CONVERT_MODE_PINYIN_FIRST);
        $result = [];
        if ($isPinyin)
        {
            $result['pinyin'] = [];
        }
        if ($isPinyinSound)
        {
            $result['pinyinSound'] = [[]];
        }
        if ($isPinyinSoundNumber)
        {
            $result['pinyinSoundNumber'] = [];
        }
        if ($isPinyinFirst)
        {
            $result['pinyinFirst'] = [];
        }

        if ((($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND) === Pinyin::CONVERT_MODE_PINYIN_SOUND))
        {
            if (null === $wordSplit)
            {
                $result['pinyinSound'] = $pinyinSounds;
            }
            else
            {
                foreach ($pinyinSounds as $pinyinSoundItem)
                {
                    $result['pinyinSound'][] = implode($wordSplit, $pinyinSoundItem);
                }
            }
        }

        foreach ($pinyinSounds as $pinyinSound)
        {
            $itemResult = $this->parseSoundItem($pinyinSound, $mode);
            if ($isPinyin)
            {
                $result['pinyin'][] = null === $wordSplit ? $itemResult['pinyin'] : implode($wordSplit, $itemResult['pinyin']);
            }
            if ($isPinyinSoundNumber)
            {
                $result['pinyinSoundNumber'][] = null === $wordSplit ? $itemResult['pinyinSoundNumber'] : implode($wordSplit, $itemResult['pinyinSoundNumber']);
            }
            if ($isPinyinFirst)
            {
                $result['pinyinFirst'][] = null === $wordSplit ? $itemResult['pinyinFirst'] : implode($wordSplit, $itemResult['pinyinFirst']);
            }
        }

        if ($isPinyin)
        {
            $result['pinyin'] = $this->uniqueResult($result['pinyin']);
        }
        if ($isPinyinSoundNumber)
        {
            $result['pinyinSoundNumber'] = $this->uniqueResult($result['pinyinSoundNumber']);
        }
        if ($isPinyinFirst)
        {
            $result['pinyinFirst'] = $this->uniqueResult($result['pinyinFirst']);
        }

        return $result;
    }

    /**
     * 把字符串转为拼音数组结果.
     *
     * @param string $string
     * @param bool   $splitNotPinyinChar 分割无拼音字符。如果为true，如123结果分割为['1','2','3']；如果为false，如123结果分割为['123']
     *
     * @return array
     */
    protected function getResult($string, $splitNotPinyinChar = true)
    {
        $len = mb_strlen($string, 'UTF-8');
        $list = [];
        $noResultItem = null;
        for ($i = 0; $i < $len; ++$i)
        {
            $word = mb_substr($string, $i, 1, 'UTF-8');
            if (isset(Chinese::$chineseData['chars'][$word]))
            {
                if (!$splitNotPinyinChar && null !== $noResultItem)
                {
                    $list[] = $noResultItem;
                    $noResultItem = null;
                }
                $list[] = Chinese::$chineseData['chars'][$word];
            }
            else
            {
                if ($splitNotPinyinChar)
                {
                    $list[] = [
                        JSONIndex::INDEX_PINYIN => $word,
                    ];
                }
                else
                {
                    if (null === $noResultItem)
                    {
                        $noResultItem[JSONIndex::INDEX_PINYIN] = '';
                    }
                    $noResultItem[JSONIndex::INDEX_PINYIN] .= $word;
                }
            }
        }
        if (!$splitNotPinyinChar && null !== $noResultItem)
        {
            $list[] = $noResultItem;
        }

        return $list;
    }

    private function parseSoundItem($array, $mode)
    {
        static $pattern, $splitSeparator;
        if (null === $pattern)
        {
            $pattern = '/([' . implode('', array_keys(Chinese::$chineseData['pinyinSound'])) . '])/u';
        }
        if (null === $splitSeparator)
        {
            $splitSeparator = '/' . uniqid('', true) . '/';
        }
        $isPinyin = (($mode & Pinyin::CONVERT_MODE_PINYIN) === Pinyin::CONVERT_MODE_PINYIN);
        $isPinyinSoundNumber = (($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER) === Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER);
        $isPinyinFirst = (($mode & Pinyin::CONVERT_MODE_PINYIN_FIRST) === Pinyin::CONVERT_MODE_PINYIN_FIRST);

        $result = [];

        if ($isPinyin)
        {
            $result['pinyin'] = [];
        }
        if ($isPinyinSoundNumber)
        {
            $result['pinyinSoundNumber'] = [];
        }
        if ($isPinyinFirst)
        {
            $result['pinyinFirst'] = [];
        }

        if ($isPinyin)
        {
            $result['pinyin'] = explode($splitSeparator, preg_replace_callback(
                $pattern,
                function ($matches) {
                    return Chinese::$chineseData['pinyinSound'][$matches[0]]['ab'];
                },
                implode($splitSeparator, $array)
            ));
        }

        foreach ($array as $pinyinSoundItem)
        {
            if ($isPinyinSoundNumber)
            {
                $tone = null;
                $str = preg_replace_callback(
                    $pattern,
                    function ($matches) use (&$tone) {
                        $tone = Chinese::$chineseData['pinyinSound'][$matches[0]]['tone'];

                        return Chinese::$chineseData['pinyinSound'][$matches[0]]['ab'];
                    },
                    $pinyinSoundItem,
                    1
                );
                if (null === $tone)
                {
                    $result['pinyinSoundNumber'][] = $str;
                }
                else
                {
                    $result['pinyinSoundNumber'][] = $str . $tone;
                }
            }
            if ($isPinyinFirst)
            {
                $result['pinyinFirst'][] = mb_substr(preg_replace_callback(
                    $pattern,
                    function ($matches) {
                        return Chinese::$chineseData['pinyinSound'][$matches[0]]['ab'];
                    },
                    $pinyinSoundItem,
                    1
                ), 0, 1);
            }
        }

        return $result;
    }
}
