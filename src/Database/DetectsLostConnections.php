<?php namespace ZhiYangLee\Lego\Database;

/**
 * copy form Laravel Database
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Database/DetectsLostConnections.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */

use Exception;
use ZhiYangLee\Lego\Support\Str;

trait DetectsLostConnections
{
    /**
     * Determine if the given exception was caused by a lost connection.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function causedByLostConnection(Exception $e)
    {
        $message = $e->getMessage();

        return Str::contains($message, [
            'server has gone away',
            'no connection to the server',
            'Lost connection',
            'is dead or not enabled',
            'Error while sending',
            'decryption failed or bad record mac',
            'server closed the connection unexpectedly',
            'SSL connection has been closed unexpectedly',
            'Deadlock found when trying to get lock',
            'Error writing data to the connection',
        ]);
    }
}
