<?php

namespace Yurun\Util\Chinese;

abstract class Util
{
    public static function mbLtrim($string, $trim_chars = '\s')
    {
        return preg_replace('/^[' . $trim_chars . ']*(.*?)$/u', '\\1', $string);
    }

    public static function mbRtrim($string, $trim_chars = '\s')
    {
        return preg_replace('/^(.*?)[' . $trim_chars . ']*$/u', '\\1', $string);
    }
}
