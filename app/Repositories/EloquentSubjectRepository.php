<?php namespace App\Repositories;

use App\Tutor;
use App\Subject;
use App\Events\RaisesEvents;
use App\Events\UserWasEdited;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{

    use RaisesEvents;

    /**
     * @var Subject
     */
    protected $subject;

    /**
     * @param Subject $subject
     * @return void
     */
    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function getDescendantsById($id)
    {
        $descendants = $this->subject->find($id)->descendants()->get();

        if ($descendants->count() !== 0) 
            {
                return $descendants;
            }

        if ($descendants->count() == 0) {
            return $this->subject
            ->newQuery()
            ->whereId($id)
            ->get();
        }
    }

    /**
     * Find the subject with a given id
     *
     * @param Integer $id
     * @return Subject
     */
    public function findById($id)
    {
        return $this->subject
            ->whereId($id)
            ->first();
    }

    /**
     * Get the subjects by given ids
     *
     * @param  Array $ids
     * @return Collection
     */
    public function getByIds(Array $ids)
    {
        return $this->subject
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Get all subjects that are at a given depth
     *
     * @param  Integer $depth
     * @return Collection
     */
    public function getByDepth($depth)
    {
        return $this->subject
            ->withDepth()
            ->having('depth', '=', $depth)
            ->defaultOrder()
            ->get();
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
        return $this->subject
            ->withDepth()
            ->having('depth', '>=', $depth)
            ->defaultOrder()
            ->get()
            ->linkNodes();
    }

    /**
     * Sync given subjects with a given tutor
     *
     * @param  Tutor $tutor
     * @param  Collection $subjects
     * @return self
     */
    public function syncWithTutor(Tutor $tutor, Collection $subjects)
    {
        // Sync
        $tutor->subjects()->sync($subjects);
        // Raise
        $this->raise(new UserWasEdited($tutor));
        // Return
        return $this;
    }

}
