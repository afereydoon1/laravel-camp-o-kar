<?php


namespace App\Service\Payment;


use App\Models\Discount;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Zarinpal\Laravel\Facade\Zarinpal;

class BuyMembershipPaymentService
{
    /**
     * @var Membership
     */
    protected $membership;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var integer
     */
    protected $amount;
    /**
     * @var integer
     */
    protected $discount;
    /**
     * @var integer
     */
    protected $discounted_price;
    /**
     * @var array
     */
    protected $results;
    /**
     * @var RedirectResponse
     */
    protected $response;
    protected $user_membership;

    /**
     * try to connect zarinpal or redirect back and notify
     * with correct session
     *
     * @return RedirectResponse
     */
    public function redirectToZarinpal()
    {
        return $this->handle();
    }

    public function handle()
    {
        return $this->setProps()
                    ->canUserButMembership()
                    ->checkAmountMoreThanThousand()
                    ->getResponseFromZarinpal()
                    ->tryToConnectZarinpal()
                    ->getResponse();
    }

    protected function getResponse(): RedirectResponse
    {
        return $this->response;
    }


    protected function setProps()
    {
        $this->membership = Membership::findOrFail(request()->membership_id);
        $this->user = request()->user();
        $this->amount = $this->membership->toman_price;

        return $this;
    }

    protected function canUserButMembership()
    {
        if ($this->isUserHasHigherMembership()) {

            session()->flash('notify', [
                'title' => 'به مشکلی خوردیم!',
                'text' => 'شما دارای اشتراک ' . $this->user_membership->name . ' هستید!!!',
                'confirm_text' => 'یادم نبود',
                'icon' => 'error'
            ]);

            $this->response = back();
        }

        return $this;
    }

    protected function checkAmountMoreThanThousand()
    {
        if ($this->amount < 1000) {

            session()->flash('notify', [
                'title' => 'به مشکلی خوردیم!',
                'text' => 'قیمت اشتراک ویژه نباید از هزار تومن کمتر باشد!!!',
                'confirm_text' => 'اوکی!',
                'icon' => 'error'
            ]);

            $this->response = back();
        }

        return $this;
    }

    protected function getResponseFromZarinpal()
    {
        $this->results = Zarinpal::request(
            url(route('callback.membership')),
            $this->amount,
            $this->membership->name
        );

        return $this;
    }

    protected function tryToConnectZarinpal()
    {
        if ($this->isResponseSet()) {
            if ($this->isAuthoritySet()) {

                $this->savePayment();

                Zarinpal::redirect();
            }

            $this->connectionFailed();
        }

        return $this;
    }


    /**
     * @return bool
     */
    protected function isUserBoughtMembership()
    {
        return !! $this->membership->users()->where('user_id', $this->user->id)->count();
    }

    /**
     * @return bool
     */
    protected function isUserHasHigherMembership()
    {
        $this->user_membership = $this->user->current_membership;
        if (! $this->user_membership) {
            return false;
        }

        return $this->user_membership->is_yearly === $this->membership->is_yearly
            && $this->user_membership->priority >= $this->membership->priority;
    }

    /**
     * @return bool
     */
    protected function isResponseSet(): bool
    {
        return !isset($this->response) && empty($this->response);
    }

    /**
     * @return bool
     */
    protected function isAuthoritySet(): bool
    {
        return isset($this->results['Authority']) && !empty($this->results['Authority']);
    }

    protected function savePayment(): void
    {
        Payment::create([
            'price' => $this->amount,
            'original_price' => $this->membership->toman_price,
            'discounted_price' => 0,
            'paymentable_id' => $this->membership->id,
            'paymentable_type' => get_class($this->membership),
            'user_id' => $this->user->id,
            'authority' => $this->results['Authority']
        ]);
    }

    protected function connectionFailed(): void
    {
        session()->flash('notify', [
            'title' => 'به مشکلی خوردیم!',
            'text' => 'در ارتباط با زرین پال به مشکل خوردیم!!!',
            'confirm_text' => 'اوکی بعدا خرید میکنم!',
            'icon' => 'error'
        ]);

        $this->response = back();
    }

}
