<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Service\Payment\BuyMembershipPaymentService;
use App\Service\Payment\VerifyMembershipPaymentService;

class BuyMembershipController extends Controller
{
    public function buy(BuyMembershipPaymentService $paymentService)
    {
        return $paymentService->redirectToZarinpal();
    }

    public function callback(VerifyMembershipPaymentService $paymentService)
    {
        return $paymentService->verifyRequest();
    }
}
