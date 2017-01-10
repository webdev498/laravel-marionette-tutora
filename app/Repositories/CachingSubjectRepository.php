<?php namespace App\Repositories;

use Illuminate\Contracts\Cache\Repository as Cache;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class CachingSubjectRepository implements SubjectRepositoryInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;

    /**
     * Create the repository.
     *
     * @param  Cache                      $cache
     * @param  SubjectRepositoryInterface $subjects
     * @return void
     */
    public function __construct(
        Cache                      $cache,
        SubjectRepositoryInterface $subjects
    ) {
        $this->cache    = $cache;
        $this->subjects = $subjects;
    }

    /**
     * Method overloading
     *
     * @param  string $name
     * @param  Array  $argments
     * @return mixed
     */
    public function __call($name, Array $argments)
    {
        return call_user_func_array([$this->subjects, $name], $argments);
    }


    /**
     * Find the subject with a given id
     *
     * @param Integer $id
     * @return Subject
     */
    public function findById($id)
    {
        $key = "subject.id={$id}";
        return $this->cache->rememberForever($key, function () use ($id) {
            return $this->subjects->findById($id);
        });
    }

    /**
     * Get the subjects by given ids
     *
     * @param  Array $ids
     * @return Collection
     */
    public function getByIds(Array $ids)
    {
        return $this->subjects->getByIds($ids);
    }

    /**
     * Get all subjects that are at a given depth
     *
     * @param  Integer $depth
     * @return Collection
     */
    public function getByDepth($depth)
    {
        $key = "subject.depth={$depth}";
        return $this->cache->rememberForever($key, function () use ($depth) {
            return $this->subjects->getByDepth($depth);
        });
    }

    /**
     * Get all subjects that're decendents of a given depth, in their
     * default order, eageriy linked.
     *
     * @param  Integer $depth
     * @return Collection
     */
    public function getDescendentsByDepth($depth)
    {
        $key = "subject.depth>={$depth}";
        return $this->cache->rememberForever($key, function () use ($depth) {
            return $this->subjects->getDescendentsByDepth($depth);
        });
    }

}
