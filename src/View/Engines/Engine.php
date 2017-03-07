<?php namespace ZhiYangLee\Lego\View\Engines;

/**
 *  copy form Laravel View
 *
 *  https://github.com/laravel/framework/blob/5.4/src/Illuminate/View/Engines/EngineInterface.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/6
 *
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