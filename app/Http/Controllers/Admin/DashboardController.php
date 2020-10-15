<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    protected $payments;

    public function index()
    {
        return Payment::whereIsPayed(true)
                ->get()
                ->groupBy('type')
                ->map(function (Collection $payments) {
                    return [
                        'price' => $payments->sum('price'),
                        'discounted_price' => $payments->sum('discounted_price'),
                    ];
                });
    }

    public function charts()
    {
        $this->payments = Payment::whereIsPayed(true)->get();

        $thirtyCollection = $this->getPastThirtyDaysPayment();

        $pastDays = collect()->times(7, function ($number) {
            return [
                'data' => [
                    'label' => verta()->subDays($number - 1)->format('l'),
                    'data' => 0
                ],
                'time' => now()->subDays($number - 1)->format('Y-m-d')
            ];
        })->pluck('data', 'time')
            ->sortKeys();

        $paymentsCollection = $this->payments
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('type')
            ->map
            ->groupBy('created_time')
            ->map(function (Collection $payments) use ($pastDays) {
                return $pastDays->merge($payments->map(function ($payment) {
                    return [
                        'label' => verta($payment->first()->created_at)->format('l'),
                        'data' => $payment->sum('price')
                    ];
                }));
            });
        $paymentsCollection['membership'] = $paymentsCollection['membership'] ?? $pastDays;
        $paymentsCollection['file'] = $paymentsCollection['file'] ?? $pastDays;

        return $paymentsCollection->toBase()->merge(collect(['total' => $thirtyCollection]))
            ->map(function ($payments) {
                return [
                    'labels' => $payments->pluck('label'),
                    'data' => $payments->pluck('data'),
                ];
            });
    }

    /**
     * @return Collection
     */
    protected function getPastThirtyDaysPayment(): Collection
    {
        $pastDays = collect()->times(30, function ($number) {
            return [
                'data' => [
                    'label' => verta()->subDays($number - 1)->format('Y-m-d'),
                    'data' => 0
                ],
                'time' => now()->subDays($number - 1)->format('Y-m-d')
            ];
        })->pluck('data', 'time')
            ->sortKeys();

        $paymentsCollection = $this->payments
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('created_time')
            ->map(function (Collection $payments) {
                return [
                    'label' => verta($payments->first()->created_at)->format('Y-m-d'),
                    'data' => $payments->sum('price')
                ];
            });

        return $paymentsCollection
            ? $pastDays->merge($paymentsCollection)
            : $pastDays;
    }

}
