<?php namespace App\Transformers;

use App\Tutor;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class QualificationsTransformer extends TransformerAbstract
{

    /**
     * List of default resources to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'universities',
        'alevels',
        'others',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Tutor $tutor)
    {
        return [];
    }

    protected function includeUniversities(Tutor $tutor)
    {
        return $this->collection($tutor->qualificationUniversities, function ($university) {
            return [
                'university'     => (string)  $university->university,
                'level'          => (string)  $university->level,
                'subject'        => (string)  $university->subject,
                'still_studying' => (boolean) $university->still_studying,
            ];
        });
    }

    protected function includeAlevels(Tutor $tutor)
    {
        return $this->collection($tutor->qualificationAlevels, function ($alevel) {
            return [
                'college'        => (string)  $alevel->college,
                'grade'          => (string)  $alevel->grade,
                'subject'        => (string)  $alevel->subject,
                'still_studying' => (boolean) $alevel->still_studying,
            ];
        });
    }

    protected function includeOthers(Tutor $tutor)
    {
        return $this->collection($tutor->qualificationOthers, function ($other) {
            return [
                'location'       => (string)  $other->location,
                'level'          => (string)  $other->level,
                'subject'        => (string)  $other->subject,
                'still_studying' => (boolean) $other->still_studying,
            ];
        });
    }

}
