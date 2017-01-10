<?php namespace App\Validators;

use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use Illuminate\Contracts\Auth\Guard as Auth;

class EditLessonBookingValidator extends Validator
{
    protected $bookings;

    protected $auth;

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(&$data)
    {
        // Lookups
        $bookings = app(LessonBookingRepositoryInterface::class);
        $auth = app(Auth::class);
        $user = $auth->user();
        $booking = $bookings->findByUuid($data['uuid']);
        $confirmed = $booking->status == LessonBooking::CONFIRMED ? true : false;

        $rules = [
            'uuid'         => ['required'],
            'date'         => ['required', 'date_format:d/m/Y'],
            'time'         => ['required', 'date_format:H:i'],
            'duration'     => ['required', 'regex:/^(?:\d+\s?:)?\s?\d{2}$/'],
            'future'       => ['boolean'],
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
            $start = strtodate($data['date'].' '.$data['time']);

            $data['start']  = $start;
        
            if ($confirmed && ! $user->isAdmin()) {
                $rules['start'] = [
                    'after:1 hours',
                    'before:1 year',
                ];
            }

            if (! $confirmed && ! $user->isAdmin()) {
                $rules['start'] = [
                    'after:24 hours',
                    'before:1 year',
                ];
            }

            loginfo($rules);
            loginfo($user->isAdmin());
        }

        if (isset($data['rate']) && $data['rate'] && ! $user->isAdmin()) {
            $tutorRateRange = $booking->lesson->relationship->tutor->profile->getAllowedRateRange();
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
