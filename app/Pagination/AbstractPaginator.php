<?php namespace App\Pagination;

use App\Pagination\Presenters\TutoraPresenter;
use Illuminate\Contracts\Pagination\Presenter;

abstract class AbstractPaginator
{

    /**
     * The number of items to be shown per page.
     *
     * @var int
     */
    const PER_PAGE = 15;

    /**
     * Create a new paginator instance.
     *
     * @param  mixed    $items
     * @param  int      $total
     * @param  int|null $currentPage
     * @param  array    $options (path, query, fragment, pageName)
     * @return void
     */
    public function paginate(
        $items,
        $total,
        $page = null,
        array $options = []
    ) {
        return new LengthAwarePaginator($items, $total, static::PER_PAGE, $page, $options);
    }

}
