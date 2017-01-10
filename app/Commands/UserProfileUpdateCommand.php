<?php namespace App\Commands;

class UserProfileUpdateCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $tagline       = null,
        $rate          = null,
        $travel_radius = null,
        $bio           = null,
        $short_bio     = null
    ) {
        $this->tagline       = $tagline;
        $this->rate          = $rate;
        $this->travel_radius = $travel_radius;
        $this->bio           = $bio;
        $this->short_bio     = $short_bio;
    }

}
