<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Service\Payment\BuyPaymentService;
use App\Service\Payment\VerifyPaymentService;

class PaymentController extends Controller
{
    public function buy(BuyPaymentService $buyPaymentService)
    {
        return $buyPaymentService->redirectToZarinpal();
    }

    public function callback(VerifyPaymentService $paymentService)
    {
        return $paymentService->verifyRequest();
    }
}
