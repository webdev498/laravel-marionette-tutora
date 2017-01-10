<?php

namespace App\Handlers\Events\Reminders;

use App\Events\MessageLineWasWritten;
use App\Handlers\Events\EventHandler;
use App\Message;
use App\MessageLine;
use App\Relationship;
use App\Reminder;
use App\Repositories\Contracts\MessageLineRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Student;
use App\Tutor;
use Carbon\Carbon;

class CreateMessageLineWrittenReminders extends EventHandler
{

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;
    
    /**
     * @var MessageLineRepositoryInterface
     */
    protected $lines;

    /**
     * Create the event handler.
     *
     * @param  ReminderRepositoryInterface $reminders
     * @param  MessageRepositoryInterface $reminders
     * @return void
     */
    public function __construct(
        ReminderRepositoryInterface   $reminders,
        MessageRepositoryInterface    $messages,
        MessageLineRepositoryInterface $lines
    ) {
        $this->reminders = $reminders;
        $this->messages = $messages;
        $this->lines = $lines;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasMade $event
     * @return void
     */
    public function handle(MessageLineWasWritten $event)
    {
        
        // Lookups
        $line  = $event->line;
        $message = $line->message;
        
        $relationship = $message->relationship;
        $sender = $line->user;

        // Create Reminders
        if ($sender instanceof Student) {
            $this->createRemindersForTutor($line, $message, $relationship, $sender);
        }
        if ($sender instanceof Tutor) {
            $this->createRemindersForStudent($line, $message, $relationship, $sender);
        }

    }

    protected function createRemindersForTutor(MessageLine $line, Message $message, Relationship $relationship, Student $student)
    {
        $count = $this->messages->countMessageLinesById($message->id);

        if ($count == 1) {

            $firstReminderDate = $this->getFirstReminderDateForTutor($line);
            $secondReminderDate = $this->getSecondReminderDateForTutor($line);

            $this->createFirstReminderForTutor($firstReminderDate, $line);
            $this->createSecondReminderForTutor($secondReminderDate, $line);
        } 
    }

    protected function createRemindersForStudent(MessageLine $line, Message $message, Relationship $relationship, Tutor $tutor)
    {
        // If the tutor can help, and this is their first message to the student, create reminder for student.
        $count = $this->lines->countByMessageAndUser($message, $tutor);

        if ($relationship->status !== RELATIONSHIP::MISMATCHED && $relationship->status !== RELATIONSHIP::REQUESTED_BY_TUTOR && $count == 1) {

            $firstReminderDate = $this->getFirstReminderDateForStudent($line);
            $secondReminderDate = $this->getSecondReminderDateForStudent($line);
            $this->createFirstReminderForStudent($firstReminderDate, $line);
            $this->createSecondReminderForStudent($secondReminderDate, $line);
        }

    }

    protected function createFirstReminderForTutor(Carbon $date, MessageLine $line)
    {

        if ($date) {
            $reminder = Reminder::make(Reminder::FIRSTNOREPLY, $date);
            $this->reminders->saveForMessageLine($line, $reminder);
        }
    }


    protected function createSecondReminderForTutor(Carbon $date, MessageLine $line)
    {

        if ($date) {
            $reminder = Reminder::make(Reminder::SECONDNOREPLY, $date);
            $this->reminders->saveForMessageLine($line, $reminder);
        }
    }

    protected function createFirstReminderForStudent(Carbon $date, MessageLine $line)
    {
        if ($date) {
            $reminder = Reminder::make(Reminder::FIRSTNOREPLY_STUDENT, $date);
            $this->reminders->saveForMessageLine($line, $reminder);
        }
    }

    protected function createSecondReminderForStudent(Carbon $date, MessageLine $line)
    {
        if ($date) {
            $reminder = Reminder::make(Reminder::SECONDNOREPLY_STUDENT, $date);
            $this->reminders->saveForMessageLine($line, $reminder);
        }
    }

    protected function getFirstReminderDateForTutor(MessageLine $line)
    {
        $minutes  = config('messages.first_reminder_to_reply_period_for_tutor', 2880);
        $remindAt = $line->created_at->copy()->addMinutes($minutes);
        return $this->checkDate($remindAt);
    }

    protected function getSecondReminderDateForTutor(MessageLine $line)
    {
        $minutes  = config('messages.second_reminder_to_reply_period_for_tutor', 2880);
        $remindAt = $line->created_at->copy()->addMinutes($minutes);
        return $this->checkDate($remindAt);
    }

    protected function getFirstReminderDateForStudent(MessageLine $line)
    {
        $minutes  = config('messages.first_reminder_to_reply_period_for_student', 1440);
        $remindAt = $line->created_at->copy()->addMinutes($minutes);
        return $this->checkDate($remindAt);
    }

    protected function getSecondReminderDateForStudent(MessageLine $line)
    {
        $minutes  = config('messages.second_reminder_to_reply_period_for_student', 2880);
        $remindAt = $line->created_at->copy()->addMinutes($minutes);
        return $this->checkDate($remindAt);
    }
    
    protected function checkDate($remindAt)
    {
        $now = Carbon::now();

        if ($remindAt->gt($now)) {
            return $remindAt;
        }
        return null;
    }
}
