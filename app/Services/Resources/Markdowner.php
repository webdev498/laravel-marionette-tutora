<?php 

namespace Services\Resources;

use League\CommonMark\CommonMarkConverter;

class Markdowner
{
	public function toHTML($text)
	{
		$converter = new CommonMarkConverter();
		$text = $converter->convertToHtml($text);
		return $text;
	}
}