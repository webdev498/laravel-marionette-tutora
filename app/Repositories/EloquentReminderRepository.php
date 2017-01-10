<?php

namespace App\Repositories;

use App\Job;
use App\LessonBooking;
use App\MessageLine;
use App\Reminder;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Tutor;
use App\User;
use App\UserBackgroundCheck;
use DateTime;

class EloquentReminderRepository extends AbstractEloquentRepository implements
    ReminderRepositoryInterface
{
    /**
     * @var Reminder
     */
    protected $reminder;

    /**
     * Create an instance of the repository 
     *
     * @param  Reminder $reminder
     * @return void
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Save a reminder for a background check.
     *
     * @param  UserBackgroundCheck $backgroundCheck
     * @param  Reminder            $reminder
     *
     * @return Reminder
     */
    public function saveForBackgroundCheck(UserBackgroundCheck $backgroundCheck, Reminder $reminder)
    {
        $backgroundCheck->reminders()->save($reminder);

        return $reminder;
    }

    /**
     * Save a reminder for a given lesson booking.
     *
     * @param  LessonBooking $booking
     * @param  Reminder      $reminder
     * @return Reminder
     */
    public function saveForLessonBooking(LessonBooking $booking, Reminder $reminder)
    {
        $booking->reminders()->save($reminder);

        return $reminder;
    }

    public function saveForMessageLine(MessageLine $line, Reminder $reminder)
    {
        $line->reminders()->save($reminder);

        return $reminder;
    }

    public function saveForUser(User $user, Reminder $reminder)
    {
        $user->reminders()->save($reminder);

        return $reminder;
    }

    public function getUpcomingLessonBookingByDate(DateTime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', LessonBooking::class)
            ->where('name', '=', Reminder::UPCOMING)
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();
    }

    public function getStillPendingLessonBookingByDate(DateTime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', LessonBooking::class)
            ->where('name', '=', Reminder::STILL_PENDING)
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();
    }

    public function getReviewLessonBookingByDate(DateTime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', LessonBooking::class)
            ->where('name', '=', Reminder::REVIEW)
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();
    }

    public function getRebookableLessonBookingsByDate(Datetime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', LessonBooking::class)
            ->where('name', '=', Reminder::REBOOK)            
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();

    }

    public function getNoReplyMessageLinesByDate(Datetime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', MessageLine::class)
            ->where(function ($q) {
                return $q->where('name', '=', Reminder::SECONDNOREPLY)
                         ->orWhere('name', '=', Reminder::FIRSTNOREPLY)
                         ->orWhere('name', '=', Reminder::FIRSTNOREPLY_STUDENT)
                         ->orWhere('name', '=', Reminder::SECONDNOREPLY_STUDENT);
            })
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();

    }

    public function getGoOnlineByDate(Datetime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', Tutor::class)
            ->where('name', '=', Reminder::GO_ONLINE)
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();

    }

    public function getJobMadeLiveByDate(Datetime $date)
    {
        return $this->reminder
            ->newQuery()
            ->where('remindable_type', '=', Job::class)
            ->where('name', '=', Reminder::JOB_MADE_LIVE)
            ->where('remind_at', '<=', $date)
            ->with($this->with)
            ->get();

    }

    /**
     * Delete a given reminder
     *
     * @param  Reminder $reminder
     * @return boolean
     */
    public function delete(Reminder $reminder)
    {
        return $reminder->delete();
    }

}
