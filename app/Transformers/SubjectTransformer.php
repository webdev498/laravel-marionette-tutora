<?php namespace App\Transformers;

use App\Subject;
use League\Fractal\TransformerAbstract;

class SubjectTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Subject $subject)
    {
        return [
            'id'        => $subject->id,
            'name'      => $subject->name,
            'parent_id' => $subject->parent_id,
            'depth'     => $subject->depth,
            'children'  => $subject->children,
            'path'      => $subject->path,
            'title'     => $subject->title,
            'iconName' => $this->getIconName($subject->path),
        ];
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
