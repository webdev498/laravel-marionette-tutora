<?php namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SubjectsController extends ApiController
{

    protected $subjects;

    public function __construct(
        SubjectRepositoryInterface $subjects
    ) {
        $this->subjects = $subjects;
    }

    /**
     * @return json
     */
    public function index()
    {
        return Cache::rememberForever('subjects-tree-0', function() {

            $subjects = $this->subjects->getDescendentsByDepth(0);
            $tree = $subjects->toTree();

            return $tree;
        });
    }

}
