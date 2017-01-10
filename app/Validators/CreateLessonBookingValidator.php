<?php namespace App\Validators;

use App\Relationship;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Carbon\Carbon;

class CreateLessonBookingValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(&$data)
    {
        
        // Lookups
        $auth = app(Auth::class);
        $user = $auth->user();
        
        $relationship = $data['relationship'];

        $rules = [
            'student.uuid' => ['required', 'string'],
            'subject.id'   => ['required', 'numeric'],
            'date'         => ['required', 'date_format:d/m/Y'],
            'time'         => ['required', 'date_format:H:i'],
            'duration'     => ['required', 'regex:/^(?:\d+\s?:)?\s?\d{2}$/'],
            'repeat'       => ['required_if:trial,0', 'in:never,weekly,fortnightly'],
        ];

        $date = date_parse_from_format('d/m/Y', array_get($data, 'date'));
        $time = date_parse_from_format('H:i', array_get($data, 'time'));

        if (
            array_get($date, 'day', false)         !== false &&
            array_get($date, 'month', false)       !== false &&
            array_get($date, 'year', false)        !== false &&
            array_get($date, 'error_count', false)  === 0 &&
            array_get($time, 'hour', false)        !== false &&
            array_get($time, 'minute', false)      !== false &&
            array_get($time, 'error_count', false)  === 0
        ) {
            $data['start']  = strtodate($data['date'].' '.$data['time']);
            
            // If confirmed - can book in up to 1 hour in advance.
            if ($relationship->isConfirmed() && ! $user->isAdmin()) {
                $rules['start'] = [
                    'after:1 hours',
                    'before:1 year',
                ];
            }

            // If not confirmed, must be at least 24 hours in advance
            if (! $relationship->isConfirmed() && ! $user->isAdmin() ) {
                $rules['start'] = [
                    'after:24 hours',
                    'before:1 year',
                ];
            }
        }

        if (isset($data['rate']) && $data['rate'] && ! $user->isAdmin()) {
            $tutorRateRange = $relationship->tutor->profile->getAllowedRateRange($data['trial']);
            $minRate = $tutorRateRange['min'];
            $maxRate = $tutorRateRange['max'];

            $rules['rate'] = [
                'numeric',
                "between:$minRate,$maxRate"
            ];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'rate.between' => trans('validation.custom.booking.rate.between')
        ];
    }

}
