<?php

/**
 * Representing a page of text.
 */
class Page {

    /**
     * @var string
     */
    private $path;

    /**
     * @param  string  $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Find a page by slug.
     *
     * @static
     * @param  string  $path
     * @return Page
     */
    public static function find($path)
    {
        if (Laravel\File::exists(VIEW_PATH.$path.'.md'))
        {
            return new static($path.'.md');
        }
        return false;
    }

    /**
     * @return string
     */
    public function toHTML()
    {
        $path = VIEW_PATH.$this->path;
        $cache = CACHE_PATH.md5($this->path).'.html';
        if ( ! file_exists($cache) or filemtime($cache) < filemtime($path))
        {
            require_once LIBRARY_PATH.'markdown.php';

            $text = file_get_contents($path);
            $html = Markdown\Markdown($text);

            file_put_contents($cache, $html);

            return $html;
        }
        return file_get_contents($cache);
    }

}
