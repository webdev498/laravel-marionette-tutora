<?php namespace App\Presenters;

use App\Tutor;
use League\Fractal\ParamBag;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Transformers\SubjectsTransformer;

class SearchPresenter extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'subjects',
        'profile',
        'search',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Tutor $tutor)
    {
        return [
            'uuid'       => (string)  $tutor->uuid,
            'first_name' => (string)  $tutor->first_name,
            'last_name'  => (string)  substr($tutor->last_name, 0, 1),
        ];
    }

    /**
     * Include subjects
     *
     * @param  Tutor $tutor
     * @return Array
     */
    protected function includeSubjects(Tutor $tutor)
    {
        $tree = [];

        foreach ($tutor->subjects as $subject) {
            $path = explode(' / ', $subject->path);
            $key  = implode('.', array_splice($path, 0, 2));

            $levels = array_get($tree, $key, []);
            $levels = array_merge($levels, $path);

            array_set($tree, $key, $levels);
        }

        $subjects = [];
        foreach ($tree as $subject => $levels) {

            $s = '';
            $i = count($levels);
            $j = 0;

            foreach ($levels as $key => $value) {
                $j++;

                $s .= $key;

                if ($value) {
                    $s .= ' ('.implode(', ', $value).')';
                }

                if ($i !== $j) {
                    $s .= ', ';
                }
            }

            $subjects[] = [
                'key'   => $subject,
                'value' => $s,
            ];
        }

        return $this->collection($subjects, function ($subject) {
            return $subject;
        });
    }

    /**
     * Include profile
     *
     * @param  Tutor $tutor
     * @return Item
     */
    protected function includeProfile(Tutor $tutor)
    {
        return $this->item($tutor->profile, function ($profile) {
            return [
                'tagline'        => (string)  $profile->tagline,
                'rate'           => (integer) $profile->rate,
                'lessons_count'  => (integer) $profile->lessons_count,
                'rating'         => (integer) $profile->rating,
                'ratings_count'  => (integer) $profile->ratings_count,
                'short_bio'      => (string)  pe($profile->short_bio),
                'bio'            => (string)  pe($profile->bio),
                'travel_radius'  => (integer) $profile->travel_radius,
            ];
        });
    }

    protected function includeSearch(Tutor $tutor)
    {
        return $this->item($tutor, function ($tutor) {
            return [
                'distance' => round($tutor->distance, 2),
                'score'    => $tutor->score,
            ];
        });
    }
}

