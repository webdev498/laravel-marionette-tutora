<?php namespace App\Http\Controllers\Api\Webhooks;

use App\Billing\Contracts\BillingInterface;
use App\Events\DispatchesEvents;
use App\Http\Controllers\Api\ApiController;
use App\IdentityDocument;
use App\LessonBooking;
use App\Mailers\IdentificationMailer;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StripeController extends ApiController
{

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

     /**
     * @var LessonBookingRepositoryInterface
     */   
    protected $bookings;

    /**
     * @param  BillingInterface        $billing
     * @param  UserRepositoryInterface $users
     * @return void
     */
    public function __construct(
        BillingInterface        $billing,
        UserRepositoryInterface $users,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->billing = $billing;
        $this->users   = $users;
        $this->bookings = $bookings;
    }

    /**
     * Handle the webhook
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event   = $this->billing->event();

        $id     = array_get($payload, 'id');
        $type   = array_get($payload, 'type');
        $method = 'handle'.studly_case(str_replace('.', '_', $type));

        loginfo("[ Webhook ] Event: '{$id}', Type: '{$type}'");

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        } else {
            $message = "[ Webhook ] Missing handler: '{$method}'";
            loginfo($message);
            return response($message);
        }
    }

    protected function handleAccountUpdated(Array $payload)
    {
        try {
            $billingId = array_get($payload, 'data.object.id');

            loginfo("[ Webook ] Handler: 'handleAccountUpdated', Account: '{$billingId}'.");

            $user = $this->users->findByBillingId($billingId);

            if ( ! $user) {
                logerror('[ Webhook ] User not found');
                return response('User Not Found', 500);
            }

            $account = $this->billing->account($user);

            $new  = array_get($payload, 'data.object', []);
            $old  = array_get($payload, 'data.previous_attributes', []);
           
            if ($verification = array_get($new, 'legal_entity.verification')) {

                $identityDocument = $user->identityDocument;

                // Stripe sometimes sends old requests after the new request. This will stop updating accounts to unverified after they have been verified.
                if($identityDocument && $identityDocument->status !== 'verified') {

                    loginfo("[ Webook ] - Verification status changed to " . $verification['status']);
                    $attributes       = array_to_object((array) $verification);
                    $identityDocument = IdentityDocument::inspected(
                        $user->identityDocument,
                        $attributes->status,
                        $attributes->details
                    );

                    $user->identityDocument->save();

                    foreach ($identityDocument->releaseEvents() as $event) {
                    event($event);
                    }
                }                
            }
            
        } catch (Exception $e) {
            logerror($e->getMessage());
            return response('Not Handled', 500);
        }
    }

     /**
     *  When a payment is created for a connected account, record the Charge that resulted in the payment
     * 
     * @param  Event Payload                    $payload
     * @return void
     */   
    public function handlePaymentCreated(Array $payload)
    {
        try
        {
            $payment_id = array_get($payload, 'data.object.id');
            $billingId = array_get($payload, 'user_id');
            $source_transfer = array_get($payload, 'data.object.source_transfer');
            
            loginfo("[ Webook ] Handler: 'handlePaymentCreated', Account: '{$billingId}', Transfer: '{$source_transfer}'.");

            $transfer = $this->billing->transfer($source_transfer);
            $charge_id = $transfer->source_transaction;

            $lesson_booking = $this->bookings->findByChargeId($charge_id);
            $lesson_booking->payment_id = $payment_id;
            $lesson_booking->save();

        }
        catch (Exception $e) 
        {
            logerror($e->getMessage());
            return response('Not Handled', 500);
        }
    }

    /**
     *  When a transfer is created, find the date that it is due to be paid
     * 
     * @param  Event Payload                    $payload
     * @return void
     */   
    public function handleTransferCreated(Array $payload)
    {
        try
        {
            $transfer = array_get($payload, 'data.object.id');
            $billingId = array_get($payload, 'user_id');

            $date = Carbon::createFromTimestamp(array_get($payload, 'data.object.date'));

            loginfo("[ Webook ] Handler: 'handleTransferCreated', Account: '{$billingId}'.");

            $payments = $this->getPaymentsByTransferAndAccount($transfer, $billingId);

            foreach ($payments as $payment)
            {
                $booking = $this->bookings->findByPaymentId($payment);

                if ( ! $booking) {
                    logerror('[ Webhook ] Booking not found');
                }

                if ($booking && $date) {
                    $booking = LessonBooking::transferInTransit($booking, $date);
                    $this->bookings->save($booking);

                    foreach ($booking->releaseEvents() as $event) {
                        event($event);
                    }
                }

                
            }
        }
        catch (Exception $e)
        {
            logerror($e->getMessage());
                        logerror($e->getFile());
            logerror($e->getLine());
            return response('Not Handled', 500);
        }
    }

    public function handleTransferPaid(Array $payload)
    {
        try 
        {
            $transfer = array_get($payload, 'data.object.id');
            $billingId = array_get($payload, 'user_id');
            $date = Carbon::createFromTimestamp(array_get($payload, 'data.object.date'));

            loginfo("[ Webook ] Handler: 'handleTransferPaid', Account: '{$billingId}'.");

            $payments = $this->getPaymentsByTransferAndAccount($transfer, $billingId);
            
            foreach ($payments as $payment)
            {
                $booking = $this->bookings->findByPaymentId($payment);

                if ( ! $booking) {
                    logerror('[ Webhook ] Booking not found');
                }

                if ($booking && $date) {
                    $booking = LessonBooking::transferred($booking, $date);
                    $this->bookings->save($booking);

                    foreach ($booking->releaseEvents() as $event) {
                        event($event);
                    }
                }

                
            }
        }
        catch (Exception $e)
        {
            logerror($e->getMessage());
            return response($e->getMessage(), 500);
        }
    }

    public function handleTransferFailed(Array $payload)
    {
        try 
        {
            $transfer = array_get($payload, 'data.object.id');
            $billingId = array_get($payload, 'user_id');

            loginfo("[ Webook ] Handler: 'handleTransferFailed', Account: '{$billingId}'.");

            $payments = $this->getPaymentsByTransferAndAccount($transfer, $billingId);

            foreach ($payments as $payment)
            {
                $booking = $this->bookings->findByPaymentId($payment);

                if ( ! $booking) {
                    logerror('[ Webhook ] Booking not found');
                }

                if ($booking) {
                    
                    $booking = LessonBooking::transferFailed($booking);
                    $this->bookings->save($booking);

                    foreach ($booking->releaseEvents() as $event) {    
                        event($event);
                    }
                }

                
            }
        }
        catch (Exception $e)
        {
            logerror($e->getMessage());
            return response($e->getMessage(), 500);
        }
    }

    public function getPaymentsByTransferAndAccount($transfer, $billingId)
    {
        $balanceTransactionObject = $this->billing->balanceTransaction();
        $balanceTransactions = $balanceTransactionObject->getByTransferAndAccount($transfer, $billingId);

        $payments = [];
        
        foreach ($balanceTransactions->data as $balanceTransaction)
        {

            $source = $balanceTransaction->source;
            if (substr($source, 0, 2) == 'py') {
                $payments[] = $source;
            }
        }
        return $payments;
    }

}
