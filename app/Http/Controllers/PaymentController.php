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
            abort(400, 'Payment was not successful.');
        }

        $order = DB::transaction(function () use ($billplz) {
            return tap(Order::where('billplz_id', $billplz['id'])->firstOrFail(), function ($order) use ($billplz) {
                $order->update([
                    'status'   => 1,
                    'paid'     => true,
                    'paid_at'  => Carbon::parse($billplz['paid_at']),
                ]);
            });
        });

        return redirect()->route('customer.invoice', $order);
    }
}
