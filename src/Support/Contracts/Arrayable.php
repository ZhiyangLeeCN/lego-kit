<?php namespace ZhiYangLee\Lego\Support\Contracts;

/**
 * copy form Laravel Support
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Contracts/Support/Arrayable.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */

interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}