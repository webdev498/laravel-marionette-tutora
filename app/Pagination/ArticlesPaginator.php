<?php

namespace App\Pagination;

class ArticlesPaginator extends AbstractPaginator
{
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
        return parent::paginate($items, $total, $page, array_extend([
            'path' => relroute('admin.blog.index'),
        ], $options));
    }

}
