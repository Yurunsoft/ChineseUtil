<?php

namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese\FFIDriver;

class SwooleFFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('SwooleFFI');
    }

    /**
     * 繁体转简体.
     *
     * @param string $string
     *
     * @return array
     */
    public function toSimplified($string)
    {
        return swoole_convert_to_simplified($string);
    }

    /**
     * 简体转繁体.
     *
     * @param string $string
     *
     * @return array
     */
    public function toTraditional($string)
    {
        return swoole_convert_to_traditional($string);
    }
}
