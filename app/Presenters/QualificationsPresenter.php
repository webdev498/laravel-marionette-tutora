<?php namespace App\Presenters;

use App\Tutor;
use App\UserQualificationAlevel;
use App\UserQualificationOther;
use App\UserQualificationUniversity;

class QualificationsPresenter extends AbstractPresenter
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'universities',
        'alevels',
        'others',
        'qts',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  array $qualifications keyed up with the default includes
     * @return array
     */
    public function transform(Array $qualifications)
    {
        return [];
    }

    /**
     * Include the universities data
     *
     * @param  array $qualifications
     * @return Collection
     */
    protected function includeUniversities(Array $qualifications)
    {
        $universities = array_get($qualifications, 'universities');
        if ($universities && ! $universities->isEmpty()) {
            return $this->collection(
                $universities,
                function (UserQualificationUniversity $university) {
                    return [
                        'university'     => (string)  $university->university,
                        'subject'        => (string)  $university->subject,
                        'still_studying' => (boolean) $university->still_studying,
                        'level'          => (string)  $this->formatUniversityLevel(
                            $university->level
                        ),
                    ];
                }
            );
        }
    }

    /**
     * Include the alevels data
     *
     * @param  array $qualifications
     * @return Collection
     */
    protected function includeAlevels(Array $qualifications)
    {
        $alevels = array_get($qualifications, 'alevels');
        if ($alevels && ! $alevels->isEmpty()) {
            return $this->collection(
                $alevels,
                function (UserQualificationAlevel $alevel) {
                    return [
                        'college'        => (string)  $alevel->college,
                        'subject'        => (string)  $alevel->subject,
                        'still_studying' => (boolean) $alevel->still_studying,
                        'grade'          => (string)  $this->formatAlevelGrade(
                            $alevel->grade
                        ),
                    ];
                }
            );
        }
    }

    /**
     * Include the others data
     *
     * @param  array $qualifications
     * @return Collection
     */
    protected function includeOthers(Array $qualifications)
    {
        $others = array_get($qualifications, 'others');
        if ($others && ! $others->isEmpty()) {
            return $this->collection(
                $others,
                function (UserQualificationOther $other) {
                    return [
                        'location'       => (string)  $other->location,
                        'subject'        => (string)  $other->subject,
                        'grade'          => (string)  $other->grade,
                        'still_studying' => (boolean) $other->still_studying,
                    ];
                }
            );
        }
    }

    /**
     * Include the qualified teacher status
     *
     * @param  array $qualifications
     * @return Item
     */
    protected function includeQts(Array $qualifications)
    {
        return $this->item(
            array_get($qualifications, 'qts', []),
            function ($qts) {
                $level = $qts ? $qts->level : 'no';
                return [
                    'key'   => $level,
                    'title' => $this->formatQtsTitle($level),
                ];
            }
        );
    }


    /**
     * Format the university level
     *
     * @param  string $level
     * @return string
     */
    protected function formatUniversityLevel($level)
    {
        return trans("qualifications.university.levels.{$level}");
    }

    /**
     * Format the alevel grade
     *
     * @param  string $grade
     * @return string
     */
    protected function formatAlevelGrade($grade)
    {
        return trans("qualifications.alevel.grades.{$grade}");
    }

    protected function formatQtsTitle($level)
    {
        return trans("qualifications.teacher_status.levels.{$level}");
    }

}
