<?php namespace App\Presenters;

use DateTime;
use Carbon\Carbon;
use App\LessonBooking;

class LessonBookingPresenter extends AbstractPresenter
{

    /**
     * List of default resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'lesson',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(LessonBooking $booking)
    {
        return [
            'id'     => (string) $booking->id,
            'uuid'     => (string) $booking->uuid,
            'date'     => $this->formatDate($booking->start_at),
            'time'     => $this->formatTime($booking->start_at, $booking->finish_at),
            'location' => (string) $booking->location,
            'duration' => (string) gmdate('H:i', $booking->duration),
            'price'    => (string) '&pound;'.$booking->price,
            'rate'     => (string) '&pound;'.$booking->rate,
            'status'   => [
                'admin'   => [
                    'status'            => $booking->status,
                    'charge_status'     => $booking->charge_status,
                    'transfer_status'   => $this->formatTransferStatus($booking->transfer_status, $booking->transferred_at),
                ],
                'tutor'   => [
                    'status' => $this->formatStatus('tutor', $booking->status, $booking->charge_status),
                    'transfer_status' =>  $this->formatTransferStatus($booking->transfer_status, $booking->transferred_at),
                ],
                'student' => $this->formatStatus('student', $booking->status, $booking->charge_status),
            ],
            // Meh
            'actions'       => [
                'edit'    => ! $booking->hasPast() && ! $booking->isCancelled(),
                'cancel'  => (! $booking->hasPast() && ! $booking->isCancelled() ) || $booking->hasFailed(),
                'confirm' => ! $booking->hasPast() && $booking->isPending(),
                'refund'  => $booking->hasPast() && $booking->isCompleted(),
                'pay'     => $booking->hasFailed(),
                'retry' => $booking->hasPast() && $booking->isCompleted() && $booking->hasFailed()
            ],
        ];
    }

    /**
     * Format the status' for a given role
     */
    protected function formatStatus($for, $status, $chargeStatus)
    {
        return [
            'short' => trans("lesson.status.{$status}.{$for}.short"),
            'long'  => trans("lesson.status.{$status}.{$for}.long"),
        ];
    }

    /**
     * Format the status' for a given role
     */
    protected function formatTransferStatus($transferStatus, $transferred_at)
    {
        if ($transferStatus == LessonBooking::TRANSFER_IN_TRANSIT) {
            return [
                'class' => 'pending',
                'value' => 'Expected ' . $transferred_at->format('jS F')
            ];
        }
        if ($transferStatus == LessonBooking::TRANSFERRED) {
            
            return [
                'class' => 'success',
                'value' => $transferred_at ? 'Paid on ' . $transferred_at->format('jS F') : 'Paid'
            ];
        }

        return [
            'class' => '',
            'value' => 'Pending',
        ];
    }


    /**
     * Include lesson data
     *
     * @param  LessonBooking
     * @return Item
     */
    protected function includeLesson(LessonBooking $booking)
    {
        return $this->item($booking->lesson, (new LessonPresenter())->setDefaultIncludes([
            'subject'
        ]));
    }

}
