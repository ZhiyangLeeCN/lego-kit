<?php namespace ZhiYangLee\Lego\View;

/**
 * @author zhiyanglee<zhiyanglee@foxmail.com>
 * @date 2017/3/9
 */

use InvalidArgumentException;
use ZhiYangLee\Lego\Support\Contracts\Arrayable;
use ZhiYangLee\Lego\View\Compilers\Compiler;
use ZhiYangLee\Lego\View\Engines\Engine;

class View
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

    /**
     * All of the finished, captured sections.
     *
     * @var array
     */
    protected $sections = [];

    /**
     * The stack of in-progress sections.
     *
     * @var array
     */
    protected $sectionStack = [];

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
    public function render($view, $data = [], $mergeData = [])
    {
        $view = $this->normalizeName($view);

        $path = $this->getViewPath($view);

        $data = array_merge($mergeData, $this->parseData($data), $this->shared);

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
     * Parse the given data into a raw array.
     *
     * @param  mixed  $data
     * @return array
     */
    protected function parseData($data)
    {
        return $data instanceof Arrayable ? $data->toArray() : $data;
    }

    /**
     *  获取视图文件路径名称
     *
     * @param  string $view
     * @return string
     */
    protected function getViewPath($view)
    {
        if (is_null($this->compiler)) {

            return $this->viewsPath . '/' . $view . '.php';

        } else {

            return $this->viewsPath . '/' . $view
                . '.' . $this->compiler->getCompilerName() . '.php';

        }

    }

    /**
     * Normalize a view name.
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        return str_replace('.', '/', $name);
    }

    /**
     * Get the rendered contents of a partial from a loop.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  string  $iterator
     * @param  string  $empty
     * @return string
     */
    public function renderEach($view, $data, $iterator, $empty = 'raw|')
    {
        $result = '';

        // If is actually data in the array, we will loop through the data and append
        // an instance of the partial view to the final result HTML passing in the
        // iterated value of this data array, allowing the views to access them.
        if (count($data) > 0)
        {
            foreach ($data as $key => $value)
            {
                $data = array('key' => $key, $iterator => $value);

                $result .= $this->render($view, $data);
            }
        }

        // If there is no data in the array, we will render the contents of the empty
        // view. Alternatively, the "empty view" could be a raw string that begins
        // with "raw|" for convenience and to let this know that it is a string.
        else
        {
            if (starts_with($empty, 'raw|'))
            {
                $result = substr($empty, 4);
            }
            else
            {
                $result = $this->render($empty);
            }
        }

        return $result;
    }

    /**
     * Get the string contents of a section.
     *
     * @param  string  $section
     * @param  string  $default
     * @return string
     */
    public function yieldContent($section, $default = '')
    {
        $sectionContent = $default;

        if (isset($this->sections[$section]))
        {
            $sectionContent = $this->sections[$section];
        }

        $sectionContent = str_replace('@@parent', '--parent--holder--', $sectionContent);

        return str_replace(
            '--parent--holder--', '@parent', str_replace('@parent', '', $sectionContent)
        );
    }

    /**
     * Stop injecting content into a section and return its contents.
     *
     * @return string
     */
    public function yieldSection()
    {
        return $this->yieldContent($this->stopSection());
    }

    /**
     * Stop injecting content into a section.
     *
     * @param  bool  $overwrite
     * @return string
     */
    public function stopSection($overwrite = false)
    {
        $last = array_pop($this->sectionStack);

        if ($overwrite)
        {
            $this->sections[$last] = ob_get_clean();
        }
        else
        {
            $this->extendSection($last, ob_get_clean());
        }

        return $last;
    }

    /**
     * Append content to a given section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    protected function extendSection($section, $content)
    {
        if (isset($this->sections[$section]))
        {
            $content = str_replace('@parent', $content, $this->sections[$section]);
        }

        $this->sections[$section] = $content;
    }

    /**
     * Start injecting content into a section.
     *
     * @param  string  $section
     * @param  string  $content
     * @return void
     */
    public function startSection($section, $content = '')
    {
        if ($content === '')
        {
            if (ob_start())
            {
                $this->sectionStack[] = $section;
            }
        }
        else
        {
            $this->extendSection($section, $content);
        }
    }

    /**
     * Stop injecting content into a section and append it.
     *
     * @return string
     */
    public function appendSection()
    {
        $last = array_pop($this->sectionStack);

        if (isset($this->sections[$last]))
        {
            $this->sections[$last] .= ob_get_clean();
        }
        else
        {
            $this->sections[$last] = ob_get_clean();
        }

        return $last;
    }

}