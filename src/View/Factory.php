<?php namespace ZhiYangLee\Lego\View;

/**
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/9
 */

use InvalidArgumentException;
use ZhiYangLee\Lego\View\Compilers\Compiler;
use ZhiYangLee\Lego\View\Engines\Engine;

class Factory
{
    /**
     * Hint path delimiter value.
     *
     * @var string
     */
    const HINT_PATH_DELIMITER = '::';

    /**
     * @var string 视图根目录
     */
    protected $viewsPath;

    /**
     * @var Engine
     */
    protected $engine;

    /**
     * @var Compiler;
     */
    protected $compiler;

    /**
     * Data that should be available to all templates.
     *
     * @var array
     */
    protected $shared = [];

    public function __construct($viewsPath, Engine $engine, Compiler $compiler = null)
    {
        $this->viewsPath = $viewsPath;
        $this->engine = $engine;
        $this->compiler = $compiler;

        $this->share('__env', $this);
    }

    /**
     * Add a piece of shared data to the environment.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function share($key, $value = null)
    {
        if (! is_array($key)) {
            return $this->shared[$key] = $value;
        }

        foreach ($key as $innerKey => $innerValue) {
            $this->share($innerKey, $innerValue);
        }
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return string
     */
    public function make($view, $data = [], $mergeData = [])
    {
        $view = $this->normalizeName($view);

        $path = $this->getViewPath($view);

        $data = array_merge($mergeData, $data, $this->shared);

        if (!file_exists($path)) {

            throw new InvalidArgumentException("View [$view] not found.");

        }

        if (!is_null($this->compiler)) {

            if ($this->compiler->isExpired($path)) {
                $this->compiler->compile($path);
            }

            $path = $this->compiler->getCompiledPath($path);

        }

        return $this->engine->get($path, $data);
    }

    /**
     *  获取视图名称
     *
     * @param  string $view
     * @return string
     */
    protected function getViewPath($view)
    {
        return $this->viewsPath . '/' . $view . '.blade.php';
    }

    /**
     * Normalize a view name.
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        $delimiter = self::HINT_PATH_DELIMITER;

        if (strpos($name, $delimiter) === false) {
            return str_replace('/', '.', $name);
        }

        list($namespace, $name) = explode($delimiter, $name);

        return $namespace.$delimiter.str_replace('/', '.', $name);
    }

}