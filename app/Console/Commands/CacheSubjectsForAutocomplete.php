<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Tutor;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Cache;

class CacheSubjectsForAutocomplete extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:cache_subjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache the subjects for the search autocomplete.';
    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;

    /**
     * @param SubjectRepositoryInterface $subjects
     */
    public function __construct(
        SubjectRepositoryInterface $subjects
    ) {
        parent::__construct();

        $this->subjects = $subjects;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        Cache::forget('subjects-tree-0');

        Cache::rememberForever('subjects-tree-0', function() {

            $subjects = $this->subjects->getDescendentsByDepth(0);
            $tree = $subjects->toTree();

            return $tree;
        });
    }
}
