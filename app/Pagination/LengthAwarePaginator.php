<?php namespace App\Pagination;

use App\Pagination\Presenters\TutoraPresenter;
use Illuminate\Contracts\Pagination\Presenter;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class LengthAwarePaginator extends Paginator
{

    /**
     * Render the paginator using the given presenter.
     *
     * @param  Presenter $presenter
     * @return string
     */
    public function render(Presenter $presenter = null)
    {
        $presenter = $presenter ?: new TutoraPresenter($this);

        return $presenter->render();
    }

    /**
     * Render the paginator using the given presenter.
     *
     * @param  Presenter $presenter
     * @return string
     */
    public function getNextUrl(Presenter $presenter = null)
    {
        $presenter = $presenter ?: new TutoraPresenter($this);

        return $presenter->getNextUrl();
    }

        /**
     * Render the paginator using the given presenter.
     *
     * @param  Presenter $presenter
     * @return string
     */
    public function getPreviousUrl(Presenter $presenter = null)
    {
        $presenter = $presenter ?: new TutoraPresenter($this);

        return $presenter->getPreviousUrl();
    }


}
