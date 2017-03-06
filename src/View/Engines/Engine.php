<?php namespace ZhiYangLee\Lego\View\Engines;

/**
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/6
 */


interface Engine
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = []);

}