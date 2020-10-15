<?php


namespace App\Service\Payment;


use App\Models\Payment;
use Zarinpal\Laravel\Facade\Zarinpal;

class VerifyPaymentService
{

    /**
     * @var array|\Illuminate\Http\Request|string
     */
    protected $authority;
    /**
     * @var array|\Illuminate\Http\Request|string
     */
    protected $status;
    protected $payment;
    protected $verified_request;
    /**
     * @var \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected $response;

    public function handle()
    {
        return $this->setProps()
                ->isPropsSet()
                ->setVerifiedRequest()
                ->checkVerifyRequest()
                ->getResponse();
    }

    public function verifyRequest()
    {
        return $this->handle();
    }

    protected function getResponse()
    {
        return $this->response;
    }


    protected function setProps()
    {
        $this->authority = \request('Authority');
        $this->status = \request('Status');

        $this->payment = Payment::firstWhere('authority', $this->authority);

        return $this;
    }


    protected function isPropsSet()
    {
        if ($this->isPropsNull()) {
            session()->flash('notify', [
                'title' => 'به مشکلی خوردیم!',
                'text' => 'اطلاعات کافی نیست!!!',
                'confirm_text' => 'اوکی بعدا خرید میکنم!',
                'icon' => 'error'
            ]);

            $this->response = redirect('/');
        }
        return $this;
    }

    protected function setVerifiedRequest()
    {
        if (! empty($this->payment) && ! empty($this->authority)) {
            $this->verified_request = Zarinpal::verify('OK', $this->payment->price, $this->authority);
        }

        return $this;
    }

    protected function checkVerifyRequest()
    {
        if (! is_null($this->response)) {
            return $this;
        }

        if ($this->verified_request['Status'] === 'success') {
            $this->updatePayment();

            session()->flash('notify', [
                'title' => 'فایل را به درستی خریداری کردید',
                'text' => 'شناسه خرید: ' . $this->verified_request['RefID'],
                'confirm_text' => 'اوکی!',
            ]);

            $this->response = redirect('/dashboard/my-files');
        } elseif ($this->verified_request['Status'] === 'verified_before') {
            session()->flash('notify', [
                'title' => 'مشکلی به وجود امد',
                'text' => 'این درخواست قبلا تایید شده است',
                'confirm_text' => 'اوکی برمیگردم به صفحه اصلی!',
                'icon' => 'error'
            ]);

            $file = $this->payment->paymentable;
            $this->response = $file ? redirect($file->url_path) : redirect('/');
        } else {
            session()->flash('notify', [
                'title' => 'مشکلی به وجود امد',
                'text' => 'پرداخت شما به مشکل خورد',
                'confirm_text' => 'اوکی برمیگردم به صفحه اصلی!',
                'icon' => 'error'
            ]);

            $file = $this->payment->paymentable;
            $this->response = $file ? redirect($file->url_path) : redirect('/');
        }

        return $this;
    }


    /**
     * @return bool
     */
    protected function isPropsNull(): bool
    {
        return is_null($this->authority) || is_null($this->status) || is_null($this->payment);
    }

    protected function updatePayment(): void
    {
        $this->payment->update([
            'is_payed' => true,
            'ref_id' => $this->verified_request['RefID'],
            'extra_details' => $this->verified_request['ExtraDetail'],
        ]);

        $this->payment->paymentable
            ->users()
            ->attach(
                $this->payment->user_id,
                ['payment_id' => $this->payment->id]
            );
    }


}
