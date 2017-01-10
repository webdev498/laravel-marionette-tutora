<?php namespace App\Billing;

use Stripe\Error\Base as StripeBaseException;
use Stripe\Error\Card as StripeCardException;
use Stripe\Error\ApiConnection as StripeApiConnectionException;
use App\Billing\Exceptions\StripeCardException as CardException;
use Stripe\Error\InvalidRequest as StripeInvalidRequestException;
use Stripe\Error\Authentication as StripeAuthenticationException;
use App\Billing\Exceptions\StripeBillingException as BillingException;

abstract class AbstractStripe
{

    public function proxyStripeExceptions(callable $func)
    {
        try {
            return $func();
        }catch (StripeCardException $e) {
            throw new CardException($e);
        } catch (StripeInvalidRequestException $e) {
            throw new BillingException($e);
        } catch (StripeAuthenticationException $e) {
            throw new BillingException($e);
        } catch (StripeApiConnectionException $e) {
            throw new BillingException($e);
        } catch (StripeBaseException $e) {
            throw new BillingException($e);
        }
    }

}
