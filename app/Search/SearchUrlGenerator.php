<?php

namespace App\Search;

class SearchUrlGenerator 
{
	protected $location = null;

	protected $subject = null;

	public function __construct(Array $search)
	{
		if ($search['subject']) $this->subject = $search['subject'];

		if ($search['location']) $this->subject = $search['location'];		
	}

	public function url()
	{
		$parts = explode(' / ', $subject->path);
            $parts = array_splice($parts, 1);

            $parent = '';
            $parts  = array_map(function ($part) use (&$parent) {
                $parent = $parent.' '.$part;

                return [
                    'title' => $part,
                    'full'  => $parent,
                    'url'   => route('search.subject', [
                        'subject' => str_slug($parent),
                    ]),
                ];
            }, $parts);

            $breadcrumbs = array_merge($breadcrumbs, $parts);
	}
}