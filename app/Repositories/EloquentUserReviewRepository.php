<?php

namespace App\Repositories;

use App\Tutor;
use App\Student;
use App\UserReview;
use App\Repositories\Contracts\UserReviewRepositoryInterface;

class EloquentUserReviewRepository implements
    UserReviewRepositoryInterface
{

    /**
     * @var UserReview
     */
    protected $review;

    /**
     * Create the repository.
     *
     * @param  UserReview $review
     * @return void
     */
    public function __construct(UserReview $review)
    {
        $this->review = $review;
    }

    /**
     * Save the review for the given tutor.
     *
     * @param  UserReview $review
     * @param  Tutor      $tutor
     * @return UserReview
     */
    public function saveForTutor(UserReview $review, Tutor $tutor)
    {
        $tutor->reviews()->save($review);

        return $review;
    }

    /**
     * Get the average rating of all reviews for a given tutor.
     *
     * @param  Tutor $tutor
     * @return float
     */
    public function averageRatingByTutor(Tutor $tutor)
    {
        return $this->review
            ->where('user_id', '=', $tutor->id)
            ->avg('rating');
    }

    /**
     * Get the number of reviews for a given tutor.
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor)
    {
        return $this->review
            ->where('user_id', '=', $tutor->id)
            ->count();
    }

    /**
     * Count the number of reviews a student has left for a given tutor
     */
    public function countByStudentForTutor(Student $student, Tutor $tutor)
    {
        return $tutor->reviews()
            ->where('reviewer_id', '=', $student->id)
            ->count();
    }

    /**
     * Get by tutor
     */
    public function getByTutor(Tutor $tutor) {
        return $this->review->where('user_id', $tutor->id)->get();
    }

    public function findById($id) {
        return $this->review->where('id', $id)->first();
    }

}
