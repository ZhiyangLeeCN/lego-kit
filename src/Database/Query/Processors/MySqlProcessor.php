<?php namespace ZhiYangLee\Lego\Database\Query\Processors;

/**
 * copy form Laravel Database
 *
 * https://github.com/laravel/framework/blob/5.4/src/Illuminate/Database/Query/Processors/MySqlProcessor.php
 *
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/15
 *
 */

class MySqlProcessor extends Processor
{
    /**
     * Process the results of a column listing query.
     *
     * @param  array  $results
     * @return array
     */
    public function processColumnListing($results)
    {
        $mapping = function ($r) {
            $r = (object) $r;

            return $r->column_name;
        };

        return array_map($mapping, $results);
    }
}
