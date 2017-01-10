<?php namespace App\Search;

use Illuminate\Support\Collection;
use App\Search\Exceptions\SubjectNotFoundException;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class SubjectSearcher extends AbstractSearcher
{

    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;

    /**
     * Create an instance of the subject searcher.
     *
     * @param  SubjectRepositoryInterface $subjects
     * @return void
     */
    public function __construct(SubjectRepositoryInterface $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * Find all subjects that "match" the given query string.
     *
     * @param  String $query
     * @return Collection
     * @throws SubjectNotFoundException
     */
    public function search($query)
    {
        
        if(empty($query)) {
            
            return null;
        }

        $subjects   = $this->subjects->getDescendentsByDepth(0);
        $terms      = $this->extractTerms(trim($query));
        $termsStr   = implode('', $terms);

        $subjects = $subjects->reject(function ($subject) use ($terms, $termsStr) {
            $path = str_normalize($subject->title);

            if($path == $termsStr) {
                $subject->strictEquality = true;
                return false;
            }

            // Does the path contain every word we searched for?
            foreach ($terms as $term) {
                if ( ! str_contains($path, $term)) {
                    return true;
                }
                // remove first inclusion only
                $pos = strpos($path, $term);
                if ($pos !== false) {
                    $path = substr_replace($path, '', $pos, strlen($term));
                }
            }
            $subject->strictEquality = empty($path);
        });

        $subjects = $subjects->sortByDesc('strictEquality');

        if ($subjects->isEmpty() === true) {

            throw new SubjectNotFoundException();
        }

        return $subjects;
    }

}
