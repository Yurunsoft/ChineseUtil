<?php

namespace Yurun\Util\Chinese;

use Yurun\Util\Chinese;

class SQLiteData
{
    public static $pdo;

    public static function init()
    {
        if (null === static::$pdo)
        {
            if (isset(Chinese::$option['sqliteDbPath']))
            {
                $path = Chinese::$option['sqliteDbPath'];
            }
            else
            {
                $path = \dirname(\dirname(__DIR__)) . '/data/chineseData.sqlite';
            }
            static::$pdo = new \PDO('sqlite:' . $path, '', '');
        }
    }

    public static function getAllData()
    {
        $stmt = static::$pdo->query('select * from chars');
        if (false === $stmt)
        {
            throw new \Exception(implode(' ', static::$pdo->errorInfo()));
        }
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $s = \count($data);
        for ($i = 0; $i < $s; ++$i)
        {
            static::parseResultData($data[$i]);
        }

        return $data;
    }

    public static function getData($char, $fields = '*')
    {
        $stmt = static::$pdo->prepare('select ' . $fields . ' from chars where char = :char limit 1');
        if (false === $stmt)
        {
            throw new \Exception(implode(' ', static::$pdo->errorInfo()));
        }
        $stmt->bindValue('char', $char);
        $result = $stmt->execute();
        if (false === $result)
        {
            throw new \Exception(implode(' ', $stmt->errorInfo()));
        }
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        static::parseResultData($data);

        return $data;
    }

    private static function parseResultData(&$data)
    {
        if (isset($data['pinyin']))
        {
            $data['pinyin'] = isset($data['pinyin'][0]) ? explode(',', $data['pinyin']) : [];
        }
        if (isset($data['pinyinSound']))
        {
            $data['pinyinSound'] = isset($data['pinyinSound'][0]) ? explode(',', $data['pinyinSound']) : [];
        }
        if (isset($data['pinyinSoundNumber']))
        {
            $data['pinyinSoundNumber'] = isset($data['pinyinSoundNumber'][0]) ? explode(',', $data['pinyinSoundNumber']) : [];
        }
        if (isset($data['sc']))
        {
            $data['sc'] = isset($data['sc'][0]) ? explode(',', $data['sc']) : [];
        }
        if (isset($data['tc']))
        {
            $data['tc'] = isset($data['tc'][0]) ? explode(',', $data['tc']) : [];
        }
        if (isset($data['isSC']))
        {
            $data['isSC'] = 1 == $data['isSC'];
        }
        if (isset($data['isTC']))
        {
            $data['isTC'] = 1 == $data['isTC'];
        }
    }
}
