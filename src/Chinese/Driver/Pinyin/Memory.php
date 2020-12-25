<?php
namespace Yurun\Util\Chinese\Driver\Pinyin;

use Yurun\Util\Chinese;
use Yurun\Util\Chinese\Pinyin;

class Memory extends Base
{
    use \Yurun\Util\Chinese\Traits\MemoryInit;

    public function __construct()
    {
        $this->initData();
    }

    /**
     * 处理结果
     * @param array $list
     * @param int $mode
     * @param string $wordSplit
     * @return void
     */
    public function parseResult($list, $mode, $wordSplit)
    {
        $isPinyin = (($mode & Pinyin::CONVERT_MODE_PINYIN) === Pinyin::CONVERT_MODE_PINYIN);
        $isPinyinSound = (($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND) === Pinyin::CONVERT_MODE_PINYIN_SOUND);
        $isPinyinSoundNumber = (($mode & Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER) === Pinyin::CONVERT_MODE_PINYIN_SOUND_NUMBER);
        $isPinyinFirst = (($mode & Pinyin::CONVERT_MODE_PINYIN_FIRST) === Pinyin::CONVERT_MODE_PINYIN_FIRST);
        $result = [];
        if($isPinyin)
        {
            $result['pinyin'] = [[]];
        }
        if($isPinyinSound)
        {
            $result['pinyinSound'] = [[]];
        }
        if($isPinyinSoundNumber)
        {
            $result['pinyinSoundNumber'] = [[]];
        }
        if($isPinyinFirst)
        {
            $result['pinyinFirst'] = [[]];
        }
        foreach($list as $item)
        {
            // 拼音和拼音首字母
            $count = count($item['pinyin']);
            if($isPinyin || $isPinyinFirst)
            {
                $oldResultCount = null;
                if($isPinyin)
                {
                    $oldResultCount = count($result['pinyin']);
                    $oldResultPinyin = $result['pinyin'];
                }
                if($isPinyinFirst)
                {
                    if(null === $oldResultCount)
                    {
                        $oldResultCount = count($result['pinyinFirst']);
                    }
                    $oldResultPinyinFirst = $result['pinyinFirst'];
                }
                for($i = 0; $i < $count - 1; ++$i)
                {
                    if($isPinyin)
                    {
                        $result['pinyin'] = array_merge($result['pinyin'], $oldResultPinyin);
                    }
                    if($isPinyinFirst)
                    {
                        $result['pinyinFirst'] = array_merge($result['pinyinFirst'], $oldResultPinyinFirst);
                    }
                }
                foreach($item['pinyin'] as $index => $pinyin)
                {
                    for($i = 0; $i < $oldResultCount; ++$i)
                    {
                        $j = $index * $oldResultCount + $i;
                        if($isPinyin)
                        {
                            $result['pinyin'][$j][] = $pinyin;
                        }
                        if($isPinyinFirst)
                        {
                            $result['pinyinFirst'][$j][] = mb_substr($pinyin, 0, 1);
                        }
                    }
                }
            }
            // 拼音读音
            if($isPinyinSound || $isPinyinSoundNumber)
            {
                $oldResultCount = null;
                if(isset($item['pinyinSound']))
                {
                    $count = count($item['pinyinSound']);
                }
                else
                {
                    $count = 0;
                }
                if($isPinyinSound)
                {
                    $oldResultCount = count($result['pinyinSound']);
                    $oldResultPinyinSound = $result['pinyinSound'];
                }
                if($isPinyinSoundNumber)
                {
                    if(null === $oldResultCount)
                    {
                        $oldResultCount = count($result['pinyinSoundNumber']);
                    }
                    $oldResultPinyinSoundNumber = $result['pinyinSoundNumber'];
                }
                for($i = 0; $i < $count - 1; ++$i)
                {
                    if($isPinyinSound)
                    {
                        $result['pinyinSound'] = array_merge($result['pinyinSound'], $oldResultPinyinSound);
                    }
                    if($isPinyinSoundNumber)
                    {
                        $result['pinyinSoundNumber'] = array_merge($result['pinyinSoundNumber'], $oldResultPinyinSoundNumber);
                    }
                }
                for($index = 0; $index < $count; ++$index)
                {
                    for($i = 0; $i < $oldResultCount; ++$i)
                    {
                        $j = $index * $oldResultCount + $i;
                        if($isPinyinSound)
                        {
                            $result['pinyinSound'][$j][] = $item['pinyinSound'][$index];
                        }
                        if($isPinyinSoundNumber)
                        {
                            $result['pinyinSoundNumber'][$j][] = $item['pinyinSoundNumber'][$index];
                        }
                    }
                }
            }
        }

        if(null !== $wordSplit)
        {
            if($isPinyin)
            {
                foreach($result['pinyin'] as $index => $value)
                {
                    $result['pinyin'][$index] = implode($wordSplit, $value);
                }
            }
            if($isPinyinSound)
            {
                foreach($result['pinyinSound'] as $index => $value)
                {
                    $result['pinyinSound'][$index] = implode($wordSplit, $value);
                }
            }
            if($isPinyinSoundNumber)
            {
                foreach($result['pinyinSoundNumber'] as $index => $value)
                {
                    $result['pinyinSoundNumber'][$index] = implode($wordSplit, $value);
                }
            }
            if($isPinyinFirst)
            {
                foreach($result['pinyinFirst'] as $index => $value)
                {
                    $result['pinyinFirst'][$index] = implode($wordSplit, $value);
                }
            }
        }

        if($isPinyin)
        {
            $result['pinyin'] = $this->uniqueResult($result['pinyin']);
        }
        if($isPinyinSoundNumber)
        {
            $result['pinyinSoundNumber'] = $this->uniqueResult($result['pinyinSoundNumber']);
        }
        if($isPinyinFirst)
        {
            $result['pinyinFirst'] = $this->uniqueResult($result['pinyinFirst']);
        }
        
        return $result;
    }

    /**
     * 把字符串转为拼音数组结果
     * @param string $string
     * @param boolean $splitNotPinyinChar 分割无拼音字符。如果为true，如123结果分割为['1','2','3']；如果为false，如123结果分割为['123']
     * @return array
     */
    public function getResult($string, $splitNotPinyinChar = true)
    {
        $len = mb_strlen($string, 'UTF-8');
        $list = array();
        $noResultItem = null;
        for($i = 0; $i < $len; ++$i)
        {
            $word = mb_substr($string, $i, 1, 'UTF-8');
            if(isset(Chinese::$chineseData['chars'][$word]))
            {
                if(!$splitNotPinyinChar && null !== $noResultItem)
                {
                    $list[] = $noResultItem;
                    $noResultItem = null;
                }
                $list[] = Chinese::$chineseData['chars'][$word];
            }
            else
            {
                if($splitNotPinyinChar)
                {
                    $list[] = array(
                        'pinyin'            => [$word],
                        'pinyinSound'       => [$word],
                        'pinyinSound'       => [$word],
                        'pinyinSoundNumber' => [$word],
                    );
                }
                else
                {
                    if(null === $noResultItem)
                    {
                        $noResultItem['pinyin'][0] = '';
                    }
                    $noResultItem['pinyin'][0] .= $word;
                }
            }
        }
        if(!$splitNotPinyinChar && null !== $noResultItem)
        {
            $list[] = $noResultItem;
        }
        return $list;
    }
}