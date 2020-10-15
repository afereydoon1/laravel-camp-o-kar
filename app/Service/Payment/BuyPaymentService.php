<?php


namespace App\Service\Payment;


use App\Models\Discount;
use App\Models\File;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Zarinpal\Laravel\Facade\Zarinpal;

class BuyPaymentService
{
    /**
     * @var File
     */
    protected $file;
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
                    ->checkUserBoughtFile()
                    ->applyDiscount()
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
        $this->file = File::findOrFail(request()->file_id);
        $this->user = request()->user();
        $this->amount = $this->file->toman_price;

        return $this;
    }

    protected function checkUserBoughtFile()
    {
        if($this->isUserBoughtFile()) {

            session()->flash('notify', [
                'title' => 'به مشکلی خوردیم!',
                'text' => 'شما این فایل را خریداری کرده اید!!!',
                'confirm_text' => 'یادم نبود',
                'icon' => 'error'
            ]);

            $this->response = back();
        }
        return $this;
    }

    protected function applyDiscount()
    {
        if (request()->discount_id) {
            $this->discount = Discount::findOrFail(request()->discount_id);
            $this->amount = $this->discount->getRealPrice($this->file->toman_price);
            $this->discounted_price = $this->discount->getDiscountedPrice($this->file->toman_price);
        }

        return $this;
    }

    protected function checkAmountMoreThanThousand()
    {
        if ($this->amount < 1000) {

            session()->flash('notify', [
                'title' => 'به مشکلی خوردیم!',
                'text' => 'قیمت فایل نباید از هزار تومن کمتر باشد!!!',
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
            url(route('callback')),
            $this->amount,
            $this->file->name
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
    protected function isUserBoughtFile()
    {
        return !! $this->file->users()->where('user_id', $this->user->id)->count();
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
            'original_price' => $this->file->toman_price,
            'discounted_price' => $this->discounted_price ?? 0,
            'paymentable_id' => $this->file->id,
            'paymentable_type' => get_class($this->file),
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
