<?php

namespace Yurun\Util\Chinese\Driver\PinyinSplit;

use Yurun\Util\Chinese;

class Memory implements BaseInterface
{
    public function __construct()
    {
        // 拼音分词数据加载
        if (!isset(Chinese::$option['pinyinSplitData']))
        {
            if (!empty(Chinese::$option['pinyinSplitData']))
            {
                Chinese::$chineseData['pinyin'] = Chinese::$option['pinyinSplitData'];
            }
            elseif (empty(Chinese::$option['pinyinSplitDataPath']))
            {
                Chinese::$chineseData['pinyin'] = json_decode(file_get_contents(\dirname(\dirname(\dirname(\dirname(__DIR__)))) . '/data/pinyinData.json'), true)['split'];
            }
            else
            {
                Chinese::$chineseData['pinyin'] = json_decode(file_get_contents(Chinese::$option['pinyinSplitDataPath']), true)['split'];
            }
        }
    }

    /**
     * 拼音分词.
     *
     * @param string      $text
     * @param string|null $wordSplit
     *
     * @return array
     */
    public function split($text, $wordSplit = ' ')
    {
        if ('' === $text)
        {
            return [];
        }
        $this->parseBlock($text, $beginMaps, $endMaps, $length);
        if (!isset($beginMaps[0]))
        {
            throw new \RuntimeException('Data error');
        }
        $result = [];
        $stacks = [
            [
                'index'     => 0,
                'result'    => [[]],
            ],
        ];
        while ($stacks)
        {
            $stack = array_pop($stacks);
            $index = $stack['index'];
            if (!isset($beginMaps[$index]))
            {
                throw new \RuntimeException('Index value error');
            }
            foreach ($beginMaps[$index] as $item)
            {
                if (!$item['isPinyin'] && isset($endMaps[$index]))
                {
                    continue;
                }
                $itemNextIndex = $item['end'] + 1;
                if (!isset($beginMaps[$itemNextIndex]) && $itemNextIndex < $length - 1)
                {
                    continue;
                }
                $itemResult = [];
                foreach ($stack['result'] as $resultItem)
                {
                    $resultItem[] = $item['text'];
                    $itemResult[] = $resultItem;
                }
                if ($itemNextIndex < $length)
                {
                    $stacks[] = [
                        'index'     => $itemNextIndex,
                        'result'    => $itemResult,
                    ];
                }
                else
                {
                    $result = array_merge($result, $itemResult);
                }
            }
        }
        if (null !== $wordSplit)
        {
            foreach ($result as &$item)
            {
                $item = implode($wordSplit, $item);
            }
        }

        return $result;
    }

    private function parseBlock($text, &$beginMaps, &$endMaps, &$length)
    {
        // 把每个连续的拼音连成块
        $blocks = preg_split('/([^a-zA-Z]+)/', $text, null, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);
        $hasNoPinyinChars = isset($blocks[1]);
        if ($hasNoPinyinChars)
        {
            $oddIsPinyin = preg_match('/^([a-zA-Z]+)$/', $blocks[0]) > 0;
        }

        $relationList = Chinese::$chineseData['pinyin']['relation'];

        $length = 0;
        $beginMaps = $endMaps = [];
        // 遍历每个块
        foreach ($blocks as $blockIndex => $block)
        {
            $blockLength = mb_strlen($block, 'UTF-8');
            if ($hasNoPinyinChars)
            {
                $blockIndexIsOdd = (1 === ($blockIndex & 1));
                if ($oddIsPinyin === $blockIndexIsOdd)
                {
                    $begin = $length;
                    $length += $blockLength;
                    $beginMaps[$begin][] = [
                        'text'      => $block,
                        'isPinyin'  => false,
                        'relation'  => null,
                        'begin'     => $begin,
                        'end'       => $length - 1,
                    ];
                    continue;
                }
            }
            $tempBlockResults = [];
            // 遍历每个字
            for ($i = 0; $i < $blockLength; ++$i)
            {
                $character = mb_substr($block, $i, 1, 'UTF-8');
                foreach (array_keys($tempBlockResults) as $j)
                {
                    $tempBlockResultItem = &$tempBlockResults[$j];
                    $relation = &$tempBlockResultItem['relation'];
                    if (isset($relation[$character]))
                    {
                        if ($tempBlockResultItem['isPinyin'])
                        {
                            $tempBlockResultItem2 = $tempBlockResultItem;
                            $tempBlockResultItem2['end'] = $end = $length + $i - 1;
                            unset($tempBlockResultItem2['relation']);
                            $beginMaps[$tempBlockResultItem2['begin']][] = $tempBlockResultItem2;
                            if ($tempBlockResultItem2['isPinyin'])
                            {
                                $endMaps[$end] = true;
                            }
                        }
                        $tempBlockResultItem['isPinyin'] = isset($relation[$character]['py']);
                        $tempBlockResultItem['text'] .= $character;
                        $tempBlockResultItem['relation'] = &$relation[$character];
                    }
                    else
                    {
                        // 保存
                        $tempBlockResultItem['end'] = $end = $length + $i - 1;
                        unset($tempBlockResultItem['relation']);
                        $beginMaps[$tempBlockResultItem['begin']][] = $tempBlockResultItem;
                        if ($tempBlockResultItem['isPinyin'])
                        {
                            $endMaps[$end] = true;
                        }
                        unset($tempBlockResults[$j]);
                    }
                    unset($tempBlockResultItem, $relation);
                }
                $tempBlockResults[] = [
                    'text'      => $character,
                    'isPinyin'  => isset($relationList[$character]['py']),
                    'relation'  => &$relationList[$character],
                    'begin'     => $length + $i,
                ];
            }
            if ($tempBlockResults)
            {
                foreach ($tempBlockResults as $tempBlockResultItem)
                {
                    // 保存
                    $tempBlockResultItem['end'] = $end = $length + $i - 1;
                    unset($tempBlockResultItem['relation']);
                    $beginMaps[$tempBlockResultItem['begin']][] = $tempBlockResultItem;
                    if ($tempBlockResultItem['isPinyin'])
                    {
                        $endMaps[$end] = true;
                    }
                }
            }
            $length += $blockLength;
        }
    }
}
