<?php

/*
 * This file is part of the Base62 package
 *
 * Copyright (c) 2016-2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/base62
 *
 */

namespace Tuupola\Base62;

use Tuupola\Base62;

abstract class BaseEncoder
{
    private $options = [
        "characters" => Base62::DEFAULT,
    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, (array) $options);
    }

    public function encode($data)
    {
        if (is_integer($data)) {
            $data = [$data];
        } else {
            $data = str_split($data);
            $data = array_map(function ($character) {
                return ord($character);
            }, $data);
        }

        $converted = $this->baseConvert($data, 256, 62);

        return implode("", array_map(function ($index) {
            return $this->options["characters"][$index];
        }, $converted));
    }

    public function decode($data, $integer = false)
    {
        $data = str_split($data);
        $data = array_map(function ($character) {
            return strpos($this->options["characters"], $character);
        }, $data);

        /* Return as integer when requested. */
        if ($integer) {
            $converted = $this->baseConvert($data, 62, 10);
            return (integer) implode("", $converted);
        }

        $converted = $this->baseConvert($data, 62, 256);

        return implode("", array_map(function ($ascii) {
            return chr($ascii);
        }, $converted));
    }

    abstract public function baseConvert(array $source, $source_base, $target_base);
}