<?php namespace ZhiYangLee\Lego\Database\Query;

/**
 * copy form Laravel Database
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Database/Query/Expression.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */

class Expression
{
    /**
     * The value of the expression.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Create a new raw query expression.
     *
     * @param  mixed  $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the expression.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the value of the expression.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}
