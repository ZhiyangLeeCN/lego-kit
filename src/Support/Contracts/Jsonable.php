<?php namespace ZhiYangLee\Lego\Support\Contracts;

/**
 * copy form Laravel Support
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Contracts/Support/Jsonable.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */

interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}