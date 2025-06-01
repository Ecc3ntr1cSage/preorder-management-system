<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    public function redirect(Request $request)
    {
        $billplz = $request->input('billplz');

        if ($billplz['paid'] !== 'true') {
            Order::where('billplz_id', $billplz['id'])->delete();

            session()->flash('message', 'Transaction Unsuccessful');
            return redirect()->route('customer.payment');
        }

        $order = DB::transaction(function () use ($billplz) {
            $order = Order::where('billplz_id', $billplz['id'])->firstOrFail();

            $order->update([
                'paid'    => true,
                'paid_at' => Carbon::parse($billplz['paid_at']),
            ]);

            if ($order->discount !== 0) {
                $order->campaign->coupon->increment('usage');
            }

            // Access campaign owner's wallet
            $wallet = $order->campaign->user->wallet;

            // Update wallet details (adjust as needed)
            $wallet->update([
                'earning' => $wallet->earning + ($order->amount - $order->fee),
                'balance' => $wallet->balance + ($order->amount - $order->fee),
            ]);

            return $order;
        });


        return redirect()->route('customer.invoice', $order);
    }
}
