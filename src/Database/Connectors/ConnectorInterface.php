<?php namespace ZhiYangLee\Lego\Database\Connectors;

/**
 * copy form Laravel Database
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Database/Connectors/ConnectorInterface.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */
interface ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config);
}
