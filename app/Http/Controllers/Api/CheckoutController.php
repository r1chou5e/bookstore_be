<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;


class CheckoutController extends Controller
{
    public function payment(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->get();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item->price * $item->quantity;
        }
        return response()->json([
            'total' => $total
        ]);
    }
}
