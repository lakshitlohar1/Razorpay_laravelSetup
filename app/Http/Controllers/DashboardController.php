<?php

namespace App\Http\Controllers;
use App\Models\PaymentStatus;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $paymentStatus = PaymentStatus::where('user_id', $userId)->get();
        $Subscription = Subscription::select('tier','switch')->where('user_id', $userId)->first();
        return view('index', compact('paymentStatus', 'Subscription'));
    }

}
