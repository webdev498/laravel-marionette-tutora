<?php namespace App\Pagination\Presenters;

use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Contracts\Pagination\Presenter as PresenterContract;

class TutoraPresenter extends BootstrapThreePresenter implements PresenterContract
{

    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return string
     */
    public function render()
    {
        if ($this->hasPages())
        {
            return templ(
                '<nav class="[ layout ] pagination "><!--
                    --><div class="[ layout__item ] pagination__previous">:previous</div><!--
                    --><div class="[ layout__item ] pagination__pages">
                        <ul class="pagination__list">:list</ul>
                    </div><!--
                    --><div class="[ layout__item ] pagination__next">:next</div><!--
                --></nav>',
                [
                    'previous' => $this->getPreviousButton(),
                    'list'     => $this->getLinks(),
                    'next'     => $this->getNextButton(),
                ]
            );
        }

        return '';
    }

    /**
     * Get the previous page pagination element.
     *
     * @param  string  $text
     * @return string
     */
    public function getPreviousButton($text = 'Previous page')
    {
        return $this->paginator->currentPage() > 1
            ? templ('<a href=":url" class="pagination__link">:text</a>', [
                'text' => $text,
                'url'  => $this->paginator->url(
                    $this->paginator->currentPage() - 1
                ),
            ])
            : '&nbsp;';
    }

    /**
     * Get the next page pagination element.
     *
     * @param  string  $text
     * @return string
     */
    public function getNextButton($text = 'Next page')
    {
        return $this->paginator->hasMorePages() === true
            ? templ('<a href=":url" class="pagination__link">:text</a>', [
                'text' => $text,
                'url'  => $this->paginator->url(
                    $this->paginator->currentPage() + 1
                ),
            ])
            : '&nbsp;';
    }

    /**
     * Get the previous page Url.
     *
     * @param  string  $text
     * @return string
     */
    public function getPreviousUrl()
    {
        return $this->paginator->currentPage() > 1
            ?  $this->paginator->url(
                    $this->paginator->currentPage() - 1) : null;
                
    }

    /**
     * Get the next page pagination url.
     *
     * @param  string  $text
     * @return string
     */
    public function getNextUrl()
    {
        return $this->paginator->hasMorePages() === true
            ? $this->paginator->url(
                    $this->paginator->currentPage() + 1
                )
            : null ;
    }


    /**
     * Get HTML wrapper for an available page link.
     *
     * @param  string  $url
     * @param  int  $page
     * @param  string|null  $rel
     * @return string
     */
    public function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

        return '<li class="pagination__item">
            <a href="'.$url.'"'.$rel.' class="pagination__link">'.$page.'</a>
        </li>';
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param  string  $text
     * @return string
     */
    public function getDisabledTextWrapper($text)
    {
        return '<li class="pagination__item">
            <a class="pagination__link">'.$text.'</a>
        </li>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param  string  $text
     * @return string
     */
    public function getActivePageWrapper($text)
    {
        return '<li class="pagination__item">
            <a class="pagination__link pagination__link--active">'.$text.'</a>
        </li>';
    }

    /**
     * Get a pagination "dot" element.
     *
     * @return string
     */
    public function getDots()
    {
        return $this->getDisabledTextWrapper("...");
    }

    /**
     * Get the current page from the paginator.
     *
     * @return int
     */
    public function currentPage()
    {
        return $this->paginator->currentPage();
    }

    /**
     * Get the last page from the paginator.
     *
     * @return int
     */
    public function lastPage()
    {
        return $this->paginator->lastPage();
    }

}
