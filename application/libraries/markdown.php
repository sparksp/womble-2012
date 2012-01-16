<?php

define('MARKDOWN_EMPTY_ELEMENT_SUFFIX', '>');
define('MARKDOWN_PARSER_CLASS', 'Markdown_Parser');

require_once LIBRARY_PATH.'markdown/markdown.php';

class Markdown_Parser extends Markdown\MarkdownExtra_Parser {

	/**
	 * Extended to pass URLs through Laravel.
	 *
	 * @internal
	 * @param  array  $matches
	 * @return string
	 * @uses Laravel\URL::to()
	 */
	function _doAnchors_inline_callback($matches)
	{
		$link_text = $this->runSpanGamut($matches[2]);
		$url       = $matches[3] == '' ? $matches[4] : $matches[3];
		$title     = $matches[6];

		$url = $this->encodeAttribute($url);

		// BEGIN: Modification to pass URLs through Laravel
		if ($url[0] !== '#' and is_null(parse_url($url, PHP_URL_SCHEME)))
		{
			$url = \Laravel\URL::to($url);
		}
		// END

		$result = "<a href=\"$url\"";
		if (isset($title)) {
			$title = $this->encodeAttribute($title);
			$result .=  " title=\"$title\"";
		}

		$link_text = $this->runSpanGamut($link_text);
		$result .= ">$link_text</a>";

		return $this->hashPart($result);
	}

	/**
	 * Extended to pass URLs through Laravel.
	 *
	 * @internal
	 * @param  array  $matches
	 * @return string
	 * @uses Laravel\URL::to_asset()
	 */
	function _doImages_inline_callback($matches)
	{
		$alt_text = $matches[2];
		$url      = $matches[3] == '' ? $matches[4] : $matches[3];
		$title    = $matches[6];

		$alt_text = $this->encodeAttribute($alt_text);
		$url = $this->encodeAttribute($url);

		// BEGIN: Modification to pass URLs through Laravel
		if ($url[0] !== '#' and is_null(parse_url($url, PHP_URL_SCHEME)))
		{
			$url = Laravel\URL::to_asset($url);
		}
		// END

		$result = "<img src=\"$url\" alt=\"$alt_text\"";
		if (isset($title)) {
			$title = $this->encodeAttribute($title);
			$result .=  " title=\"$title\""; # $title already quoted
		}
		$result .= $this->empty_element_suffix;

		return $this->hashPart($result);
	}

}
