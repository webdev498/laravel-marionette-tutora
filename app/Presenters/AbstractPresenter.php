<?php namespace App\Presenters;

use DateTime;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AbstractPresenter extends TransformerAbstract
{

    use PresenterTrait;

    /**
     * Create an instance of the presenter
     *
     * @return void
     */
    public function __construct(Array $options = [])
    {
        if ($include = array_get($options, 'include')) {
            $this->defaultIncludes = array_merge_unique(
                $this->defaultIncludes,
                $include
            );
        }
    }

    /**
     * Format dates into human readable formats
     *
     * @param  DateTime $date
     * @return array
     */
    protected function formatDate(DateTime $date)
    {
        return [
            'short'    => (string) $date->format('D jS M'),
            'long'     => (string) $date->format('jS F, Y'),
            'computer' => (string) $date->format('Y-m-d'),
            'forHumans'      => (string) $date->diffForHumans(),
            'forHumansShort' => (string) $date->diffForHumans(null, true),
        ];
    }

    /**
     * Format timestamps into human readable formats
     *
     * @param  DateTime $time
     * @return array
     */
    protected function formatHumanTime(Carbon $time)
    {
        return [
            'short' => $time->lt(Carbon::now()->addHour())
                ? $time->diffForHumans()
                : $time->format('D, H:i:s'),
            'shortest' => $time->lt(Carbon::now()->addHour())
                ? $time->diffForHumans(null, true)
                : $time->format('D, H:i:s'),
            'medium' => (string) $time->format('H:i D jS M'),
            'long'   => $time->format('H:i:s, jS F Y'),
        ];
    }

    /**
     * Format times into human readable formats
     *
     * @param  DateTime $start
     * @param  DateTime $finish
     * @return array
     */
    protected function formatTime(DateTime $start, DateTime $finish)
    {
        return [
            'start'  => $start->format('H:i'),
            'finish' => $finish->format('H:i'),
        ];
    }
}
