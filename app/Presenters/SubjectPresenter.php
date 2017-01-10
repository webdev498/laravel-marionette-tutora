<?php

namespace App\Presenters;

use App\Subject;
use League\Fractal\ParamBag;

class SubjectPresenter extends AbstractPresenter
{
    /**
     * List of default resources to include
     *
     * @var array
     */
    public $defaultIncludes = [
        'children'
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  Collection $subjects
     * @return array
     */
    public function transform(Subject $subject)
    {
        return [
            'title'    => $subject->title,
            'name'     => $subject->name,
            'iconName' => $this->getIconName($subject->path),
        ];
    }

    /**
     * Include children data
     *
     * @param  Subject
     * @return Collection or null
     */
    protected function includeChildren(Subject $subject)
    {
        if ($subject->children) {
            return $this->collection($subject->children, new self());
        }
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function getIconName($path)
    {
        $icons = config('subjects.icons');
        $icon  = $icons['default'];

        $parts = explode(' / ', $path);

        $i = count($parts) - 1;
        while($i > 0 && !array_key_exists($parts[$i], $icons)) {
            $i--;
        }

        if(array_key_exists($parts[$i], $icons)) {
            $icon = $icons[$parts[$i]];
        }

        return $icon;
    }

}

