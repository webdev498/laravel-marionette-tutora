<?php

namespace App\Presenters;

use App\UserProfile;
use Carbon\Carbon;

class ProfilePresenter extends AbstractPresenter
{

    /**
     * Turn this object into a generic array
     *
     * @param  User $user
     *
     * @return array
     */
    public function transform(UserProfile $profile)
    {

        return [
            'is_featured'   => (boolean)$profile->featured,
            'status'        => (string)$profile->status,
            'admin_status'  => (string)$profile->admin_status,
            'quality'       => (integer)$profile->quality,
            'distance'      => (integer)0,
            'tagline'       => (string)$profile->tagline,
            'summary'       => (string)$profile->summary,
            'rate'          => (string)$this->formatRate($profile->rate),
            'lessons_count' => (integer)$this->formatLessonsCount($profile->lessons_count),
            'rating'        => (float)$profile->rating,
            'ratings_count' => (integer)$profile->ratings_count,
            'travel_radius' => (integer)$this->formatTravelRadius(
                $profile->travel_radius
            ),
            'response_time' => (string)$this->formatResponseTime($profile->response_time),
            'bio'           => (string)$this->formatBio($profile->bio),
            'short_bio'     => (string)$this->formatShortBio(
                $profile->short_bio,
                $profile->bio
            ),
            'vshort_bio'    => (string)$this->formatVShortBio(
                $profile->short_bio,
                $profile->bio
            ),
            'profile_score' => (float)round(
                $profile->profile_score,
                2
            ),
            'booking_score' => (float)round(
                $profile->booking_score,
                2
            ),
            'video_status'  => (string)$profile->video_status
        ];
    }

    /**
     * Format the rate
     *
     * @param  integer
     *
     * @return integer
     */
    protected function formatRate($rate)
    {
        return $rate ?: '-';
    }

    /**
     * Format the travel radius
     *
     * @param  integer
     *
     * @return integer
     */
    protected function formatTravelRadius($travelRadius)
    {
        return $travelRadius !== null ? $travelRadius : -1;
    }

    /**
     * Format the profiles bio
     *
     * @param  string
     *
     * @return string
     */
    protected function formatBio($bio)
    {
        return $bio ? pe($bio) : null;
    }

    /**
     * Format the profiles short bio
     *
     * @param  string
     *
     * @return string
     */
    protected function formatShortBio($shortBio, $bio)
    {
        // If you've not provided a short bio,
        // one will be provided for you.
        if (!$shortBio) {
            $shortBio = $bio;
            $end      = '...';
        } else {
            $end = '';
        }

        // Don't let people take the piss with line breaks
        if (substr_count(
                $shortBio,
                "\n"
            ) > 4
        ) {
            $shortBio = str_replace(
                "\n",
                ' ',
                $shortBio
            );
        }

        $shortBio = str_limit(
            $shortBio,
            300,
            $end
        );

        return pe($shortBio);
    }

    /**
     * Format the profiles short bio
     *
     * @param  string
     *
     * @return string
     */
    protected function formatVShortBio($shortBio, $bio)
    {
        // If you've not provided a short bio,
        // one will be provided for you.
        if (!$shortBio) {
            $shortBio = $bio;
            $end      = '...';
        } else {
            $end = '';
        }

        // Don't let people take the piss with line breaks
        if (substr_count(
                $shortBio,
                "\n"
            ) > 4
        ) {
            $shortBio = str_replace(
                "\n",
                ' ',
                $shortBio
            );
        }

        $shortBio = str_limit(
            $shortBio,
            120,
            $end
        );

        return $shortBio;
    }

    /**
     * Format the response time
     *
     * @param  integer
     *
     * @return string
     */
    protected function formatResponseTime($responseTime)
    {
        if ($responseTime == null) {
            return null;
        }

        if ($responseTime > config('tutors.maxResponseTime')) {
            $responseTime = config('tutors.maxResponseTime');
        }

        $_responseTime = Carbon::now()->addMinutes($responseTime)->diffForHumans(
            null,
            true
        );

        return $_responseTime;
    }

    /**
     * Format Lessons Count
     *
     * @param  integer
     *
     * @return integer
     */
    protected function formatLessonsCount($count)
    {
        switch ($count) {
            case ($count < 5):
                $count = 0;
                break;
            case ($count < 10);
                $count = 5;
                break;
            case ($count < 25);
                $count = 10;
                break;
            case ($count < 50);
                $count = 25;
                break;
            case ($count < 75);
                $count = 50;
                break;
            case ($count < 100);
                $count = 75;
                break;
            case ($count < 250);
                $count = 100;
                break;
            case ($count < 500);
                $count = 250;
                break;
            case ($count < 750);
                $count = 500;
                break;
        }

        return $count;
    }
}
