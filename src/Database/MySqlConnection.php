<?php

namespace ZhiYangLee\Lego\Database;

use ZhiYangLee\Lego\Database\Query\Processors\MySqlProcessor;
use ZhiYangLee\Lego\Database\Query\Grammars\MySqlGrammar as QueryGrammar;

class MySqlConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \ZhiYangLee\Lego\Database\Query\Grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \ZhiYangLee\Lego\Database\Query\Processors\MySqlProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new MySqlProcessor;
    }
}
