<?php namespace App\Transformers;

use App\Subject;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use Kalnoy\Nestedset\Collection as NestedCollection;

class SubjectsTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Collection $subjects)
    {
        guard_against_array_of_invalid_arguments($subjects, Subject::class);

        $nodes = new NestedCollection();

        foreach ($subjects as $subject) {
            $nodes = $nodes->merge($subject->getAncestors());
            $nodes->add($subject);
        }

        $nodes->linkNodes();

        $tree = $nodes->toTree();

        return $tree->toArray();
    }

}
