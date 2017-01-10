<?php namespace App\Http\Controllers;

use App\Commands\SearchCommand;
use App\Commands\SearchResultsCommand;
use App\Presenters\SearchResultsPresenter;
use App\Search\Exceptions\LocationNotFoundException;
use App\Search\Exceptions\SubjectNotFoundException;
use App\Search\SearchLinkGenerator;
use App\Toast;
use App\Validators\Exceptions\ValidationException;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function none(Request $request)
    {
        return view('search.none');
    }

    public function index(Request $request, $subject, $location = null)
    {

        $results = $this->dispatchFrom(SearchResultsCommand::class, $request, [
            'subject'  => $subject,
            'location' => $location,
        ]);
    

        // Redirect to page 1 if no results
        if($results->tutors->count() == 0 && $results->tutors->total() !== 0) {
            return redirect()->route('search.index', ['subject' => $subject, 'location' => $location], 301);
        }


        $_results = $this->presentItem($results, new SearchResultsPresenter(), [
            'include'   => [
                sprintf(
                    'input:subject(%s):location(%s)',
                    session('subject_'.$subject),
                    session('location_'.$location)
                ),
            ],
        ]);

        $generator = app(SearchLinkGenerator::class);
        $generator->setLocation($location);
        $generator->setSubject($subject);
        $subjects = $generator->generateSubjects();
        $locations = $generator->generateLocations();

        $_metaParameters = $_results->titles->toArray();
        $_metaParameters['noindex'] =  !$generator->isSearchALandingPage();
        
        
        return view('search.index', [
            'canonical'    => $_metaParameters['noindex'] ? null: $this->getCanonicalUrl($request, $_results),
            'results'         => $_results,
            'meta_parameters' => $_metaParameters,
            'number_of_reviews' => $results->numberOfReviews,
            'average_review'  => $results->averageReview,
            'subjects'         => $subjects,
            'locations'        => $locations,           
        ]);
    }

    public function location(Request $request, $location) 
    {
        
        $results = $this->dispatchFrom(SearchResultsCommand::class, $request, [
            'subject'  => null,
            'location' => $location,
        ]);
        
        // Redirect to page 1 if no results
        if($results->tutors->count() == 0 && $results->tutors->total() !== 0) {
            return redirect()->route('search.location', ['location' => $location], 301);
        }

        $_results = $this->presentItem($results, new SearchResultsPresenter(), [
            'include'   => [
                sprintf(
                    'input:subject(%s):location(%s)',
                    session('subject_'.null),
                    session('location_'.$location)
                ),
            ],
        ]);
        
        $generator = app(SearchLinkGenerator::class);
        $generator->setLocation($_results->titles->location);
        
        $subjects = $generator->generateSubjects();
        $locations = $generator->generateLocations();

        $_metaParameters = $_results->titles->toArray();
        $_metaParameters['noindex'] =  $request->get('page') > 1 ? false : !$generator->isSearchALandingPage();

        return view('search.index', [
            'canonical'    => $this->getCanonicalUrl($request, $_results),
            'results'         => $_results,
            'meta_parameters' => $_metaParameters,
            'number_of_reviews' => $results->numberOfReviews,
            'average_review'  => $results->averageReview,
            'subjects'         => $subjects,
            'locations'        => $locations, 
        ]);
    }

    public function create(Request $request)
    {
        
        try {
            // Throw no subject found || no location found
            list($subjects, $location) = $this->dispatchFrom(SearchCommand::class, $request);
            
            $subject  = $subjects ? str_slug($subjects->first()->title) : null;
            $location = location_to_url($location);
            
            session([
                'subject_'.$subject   => $request->subject,
                'location_'.$location => $request->location,
            ]);

            if ($subject && empty($location)) {
                return redirect()
                    ->route('search.subject', [
                        'subject' => $subject
                    ])
                    ->with([
                    'toast' => new Toast("Make sure you enter a location to view tutors who are close to you.", Toast::ERROR),
                ]);
            }

            if (empty($subject) && $location) {
                return redirect()
                    ->route('search.location', [
                        'location' => $location
                    ])
                    ->with([
                    'toast' => new Toast("Please enter a subject to view tutors who match your requirements.", Toast::ERROR),
                ]);
            }

            if (empty($subject) && empty($location)) {
                return redirect()
                ->route('search.none')
                ->with([
                    'message' => 'You need to enter and subject and location',
                    'toast' => new Toast("Enter a subject and a location to view available tutors.", Toast::ERROR),
                ]);
            }
            return redirect()
                ->route('search.index', [
                    'subject'  => $subject,
                    'location' => $location,
                ]);

        } catch (ValidationException $e) {
            return redirect()
                ->route('search.none');
        } catch (SubjectNotFoundException $e) {
            return redirect()
                ->route('search.none')
                ->withInput()
                ->with([
                    'message' => 'Please enter the subject you’re hoping to study, so we can show you a list of the right tutors for you. We have tutors for every subject and every level.',
                    'toast' => new Toast("Sorry, we couldn't find a subject using your search.", Toast::ERROR),
                ]);
        } catch (LocationNotFoundException $e) {

            return redirect()
                ->route('search.none')
                ->withInput()
                ->with([
                    'message' => 'Please enter your postcode or town, so we can show you a list of the tutors who live nearest you. Our tutors can either come to your home, or you can visit them.',
                    'toast' => new Toast("Sorry, we couldn't find a location using your query.", Toast::ERROR),
                ]);
        }
    }

    protected function getCanonicalUrl(Request $request, $results)
    {
        
        $cleanUrl = last(last($results['breadcrumbs']));
        
        $url = $cleanUrl->url;
        
        return $url;
    }
}
