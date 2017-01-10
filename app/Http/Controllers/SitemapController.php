<?php namespace App\Http\Controllers;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Resources\Article;

class SitemapController extends Controller 
{
	protected $articles;

	public function __construct(ArticleRepositoryInterface $articles)
	{
		$this->articles = $articles;
	}


    /**
     * @return Response
     */
    public function index()
    {
		$sitemap = app("sitemap"); 

		// $sitemap->setCache('laravel.sitemap', 60);

		// Locations & Subjects
		$regions = config('sitelinks.locations');
		$cities = [];
		foreach ($regions as $region) 
		{
			foreach ($region as $city => $values)
			{
			
				$cities[$city] = config('sitelinks.' . $values['subjects']);
				
			}
		}

		// Generate Static Content

		$sitemap->add(route('home'), null, '1', 'daily');
		$sitemap->add(route('about.index'), null, '1', 'daily');
		$sitemap->add(route('faqs.student'), null, '1', 'daily');
		$sitemap->add(route('faqs.tutor'), null, '1', 'daily');
		$sitemap->add(route('terms.index'), null, '1', 'daily');
		$sitemap->add(route('policy.privacy'), null, '1', 'daily');
		$sitemap->add(route('faqs.tutor'), null, '1', 'daily');
		$sitemap->add(route('locations.index'), null, '1', 'daily');

		// Generate Dynamic Content

		// Subjects
		$subjects = config('sitelinks.expanded_subjects');
		
		foreach ($subjects as $subject => $value)
		{
			$sitemap->add(route('search.subject', ['subject' => $subject]), null, '1.0', 'daily');

		}


		// Cities
		foreach ($cities as $city => $subjects) 
		{
			$sitemap->add(route('search.location', ['location' => $city]), null, '1.0', 'daily');

			foreach ($subjects as $subject => $value)
			{
				$sitemap->add(route('search.index', ['subject' => $subject, 'location' => $city]), null, '0.8', 'daily');
			}
		}

		// Articles

		$sitemap->add(route('articles.index'), null, '1.0', 'daily');
		$articles = $this->articles->getPublished();

		foreach ($articles as $article)
		{
			$sitemap->add(route('articles.show', ['slug' => $article->slug]), null, '0.8', 'weekly');
		}


		return $sitemap->render('xml');
       
    }

}
