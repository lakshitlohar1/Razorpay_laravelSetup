<?php

namespace App\Http\Controllers;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SubscriptionController extends Controller
{
    public function showSubscriptionForm()
    {
        $userId = Auth::user()->id;
        $Subscription = Subscription::select('tier','switch')->where('user_id', $userId)->first();
        return view('subscriptions.index', compact('Subscription'));
    }

    public function updateSubscription(Request $request){
        $userId = Auth::user()->id;
        $request->validate([
            'tier' => 'required|string',
            'old_tier' => 'required|string',
        ]);

        $subscription = Subscription::where('user_id', $userId)->first();
        if (!$subscription) {
            return redirect()->back()->withErrors('Subscription not found.');
        }
        // Update the subscription tier and switch value
        $subscription->switch = $request->tier; // Store the switch information
        // Save the changes to the database
        $subscription->save();
        return redirect()->back()->with('success', 'Subscription updated successfully.');
    }
}
