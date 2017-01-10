<?php namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AbstractTransformer extends TransformerAbstract
{

    use TransformerTrait;

    /**
     * Format timestamps into human readable formats
     *
     * @param  Carbon $time
     * @return array
     */
    protected function formatHumanTime(Carbon $time)
    {
        return [
            'short' => $time->lt(Carbon::now()->addHour())
                ? $time->diffForHumans()
                : $time->format('D, H:i:s'),
            'medium' => (string) $time->format('H:i D jS M'),
            'long'   => $time->format('H:i:s, jS F Y'),
        ];
    }
}
