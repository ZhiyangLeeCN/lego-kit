<?php namespace ZhiYangLee\Lego\View\Compilers;


/**
 * copy form Laravel View
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/View/Compilers/CompilerInterface.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/7
 */


interface Compiler
{
    /**
     * Get the path to the compiled version of a view.
     *
     * @param  string  $path
     * @return string
     */
    public function getCompiledPath($path);

    /**
     * Determine if the given view is expired.
     *
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path);

    /**
     * Compile the view at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path);
}