<?php

namespace App\Http\Controllers;
use Razorpay\Api\Api;
use App\Models\PaymentStatus;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PaymentController extends Controller
{
    public function handlePaymentCallback(Request $request)
    {
        // Validate the payment data here (e.g., payment ID, amount)
        $paymentId = $request->input('razorpay_payment_id');
        $amount = $request->input('amount');
        $plan = $request->input('plan');
        $userId = $request->input('user_id');

        return response()->json(['message' => 'Payment processed successfully!']);
    }

    public function createOrder(Request $request)
    {
        // dd(env('APP_NAME'), env('APP_ENV'), env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Get Razorpay credentials from environment variables
        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');
        $api = new Api($key, $secret);

        // Ensure 'price' is provided (amount in paise)
        $amount = $request->input('price');
        if ($amount <= 0) {
            return response()->json(['error' => 'Amount must be greater than 0'], 400);
        }

        $currency = 'INR';
        $receipt = 'receipt#' . uniqid(); // Generate a unique receipt ID

        try {
            $orderData = [
                'amount' => $amount, // Amount in paise (already sent in paise from frontend)
                'currency' => $currency,
                'receipt' => $receipt,
                'payment_capture' => 1 // Auto capture
            ];

            // Log order data for debugging
            \Log::info('Creating order with data:', $orderData);

            // Create order using Razorpay API
            $order = $api->order->create($orderData);

            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // public function paymentCallback(Request $request)
    // {
    //     // Handle payment confirmation
    //     $orderId = $request->input('order_id');
    //     $paymentId = $request->input('payment_id');
    //     $userId = $request->input('userId');
    //     $amount = $request->input('amount');
    //     $tier = '';
    //     if ($amount === 0) {
    //         $tier = 'Silver'; // Free tier
    //     } elseif ($amount === 2499) {
    //         $tier = 'Gold'; // Gold tier price
    //     } elseif ($amount === 9999) {
    //         $tier = 'Platinum'; // Platinum tier price
    //     }
    //     PaymentStatus::updateOrCreate(
    //         [
    //             'order_id' => $orderId, // Use order ID to ensure unique record
    //         ],
    //         [
    //             'user_id' => $userId,
    //             'payment_id' => $paymentId, // Nullable if payment fails
    //             'amount' => $amount,
    //             'status' => $paymentId ? 'success' : 'failure', // Set status based on the presence of payment ID
    //         ]
    //     );
    //     Subscription::create([
    //         'payment_statuses_id' => $payment_statuses_id,
    //         'user_id' => $userId,
    //         'tier' => $tier,
    //         'switch' =>$tier,
    //         'starts_at' => now(),
    //         'ends_at' => now()->addMonth(), // Adjust as needed
    //     ]);
    //     if ($paymentId) {
    //         // Handle additional success logic (e.g., send invoice, update user subscription)
    //         return response()->json(['success' => true]);
    //     } else {
    //         // Handle payment failure logic
    //         return response()->json(['success' => false]);
    //     }
    // }


public function paymentCallback(Request $request)
{
// Get request data
$orderId = $request->input('order_id');
$paymentId = $request->input('payment_id');
$userId = $request->input('userId');
$amount = $request->input('amount');

// Determine the tier based on the amount
$tier = '';
if ($amount == 0) {
    $tier = 'Silver'; // Free tier
} elseif ($amount == 2499) {
    $tier = 'Gold'; // Gold tier price
} elseif ($amount == 9999) {
    $tier = 'Platinum'; // Platinum tier price
}

// Step 1: Insert or update the PaymentStatus record
$paymentStatus = PaymentStatus::updateOrCreate(
    [
        'order_id' => $orderId, // Unique based on order ID
    ],
    [
        'user_id' => $userId,
        'payment_id' => $paymentId, // Nullable if payment fails
        'amount' => $amount,
        'status' => $paymentId ? 'success' : 'failure', // Status based on payment success or failure
    ]
);
// Step 2: Check if the user already has a subscription
$subscription = Subscription::where('user_id', $userId)->first();

if ($subscription) {
    // If subscription exists, update the `switch` and other fields
    $subscription->update([
        'switch' => strtolower($tier), // Update the tier switch field
        'payment_statuses_id' => $paymentStatus->id, // Add payment_statuses_id
        'ends_at' => now()->addMonth(), // Extend the subscription end date
    ]);
} else {
    // Step 3: Create a new subscription if none exists
    Subscription::create([
        'payment_statuses_id' => $paymentStatus->id,
        'user_id' => $userId,
        'tier' => strtolower($tier),
        'switch' => strtolower($tier),
        'starts_at' => now(),
        'ends_at' => now()->addMonth(), // Adjust the end date as necessary
    ]);
}

// Step 4: Return success or failure based on payment status
if ($paymentId) {
    // Handle success (e.g., send an invoice, notify user)
    return response()->json(['success' => true, 'message' => 'Payment successful, subscription updated.']);
} else {
    // Handle payment failure logic
    return response()->json(['success' => false, 'message' => 'Payment failed. Please try again.']);
}
}



    public function paymentSuccess()
    {
        $userId = Auth::user()->id;
        $Subscription = Subscription::select('tier','switch')->where('user_id', $userId)->first();
        return view('payment.success', compact('Subscription')); // Create a view for the success screen
    }

    public function paymentFailure()
    {
        $userId = Auth::user()->id;
        $Subscription = Subscription::select('tier','switch')->where('user_id', $userId)->first();
        return view('payment.failure', compact('Subscription')); // Create a view for the failure screen
    }

}
