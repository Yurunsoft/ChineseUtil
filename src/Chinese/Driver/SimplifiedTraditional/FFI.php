<?php

namespace Yurun\Util\Chinese\Driver\SimplifiedTraditional;

use Yurun\Util\Chinese\FFIDriver;

class FFI implements BaseInterface
{
    public function __construct()
    {
        FFIDriver::getHandler('FFI');
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
        return convert_to_simplified($string);
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
        return convert_to_traditional($string);
    }
}
