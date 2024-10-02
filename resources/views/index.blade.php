<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon-32x32.png" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #1e1e2f; /* Dark background */
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
            color: #ffffff;
            display: flex;
            flex-direction: column;
        }

        /* Top Menu Bar */
        nav {
            background-color: #282c34; /* Dark menu bar */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        nav h1 {
            font-size: 1.5rem;
            color: #ffffff;
        }

        nav .user-info {
            display: flex;
            align-items: center;
        }

        nav .user-info span {
            margin-right: 1rem;
            font-weight: bold;
            color: #cfcfcf; /* Light gray */
        }

        nav .logout-btn {
            padding: 0.5rem 1rem;
            background-color: #ff4c4c;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        nav .logout-btn:hover {
            background-color: #ff1f1f;
        }

        /* Main content */
        main {
            margin-top: 80px; /* Spacing to account for fixed nav bar */
            width: 80%;
            max-width: 1440px;
            margin-left: auto;
            margin-right: auto;
            padding-top: 2rem;
        }


        .btn {
            margin-top: 1rem;
            padding: 0.8rem 1.2rem;
            background: linear-gradient(135deg, rgba(163, 168, 240, 1) 0%, rgba(105, 111, 221, 1) 100%);
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn.active-btn {
            background: #ffffff;
            color: #1e1e2f;
        }

        /* Media Queries for Responsive Layout */
        @media (max-width: 768px) {
            main {
                width: 95%;
            }

            nav h1 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {

        }
        .subscription-card {
            background-color: #282c34;
            padding: 20px;
            border-radius: 10px;
            color: rgba(105, 111, 221, 1);
        }

        ul{
            list-style-type: none;
        }
.custom-padding {
    padding: 5px 10px; /* Adjust values as needed */
}
    </style>
</head>
<body>
    <!-- Top Menu Bar -->
    <nav>
        <h1><a href="{{ route('index') }}" class="text-white" style="text-decoration: none;">Home</a></h1>
        <div class="user-info">
            <span class="bg-success custom-padding" style="text-transform: uppercase;">
                Active - {{ !empty($Subscription->switch) ? $Subscription->switch : (!empty($Subscription->tier) ? $Subscription->tier : 'No Plan') }}
            </span>
            <span class="bg-warning custom-padding text-white"><a  href="{{ route('subscriptions.index') }}" class="text-mute text-white" style="text-decoration: none;">Upgrade/Downgrade</a></span>
            <span>{{ Auth::user()->name}}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="row">
            <!-- Subscription Card Section -->
            <div class="col-md-6 mb-4">
                <div class="subscription-card">
                    <h3 class="text-white">Subscription</h3>
                    <div class="card mt-3">
                        <ul class="mt-4">
                            <li class="pack">Silver (Free)</li>
                            <li id="basic" class="price bottom-bar">00.00 INR</li>
                            <li class="bottom-bar">Free-tier access with limited features.</li>
                            <li><button class="buy-btn btn btn-primary" id='Silver' data-id={{ Auth::user()->id }} data-price="0">Buy</button></li>
                        </ul>
                    </div>
                    <div class="card mt-5 active">
                        <ul class="mt-4">
                            <li class="pack">Gold (Paid)</li>
                            <li id="professional" class="price bottom-bar">2499.00 INR</li>
                            <li class="bottom-bar">Enhanced features compared to the Silver tier.</li>
                            <li><button class="buy-btn btn btn-success" id='Gold' data-id={{ Auth::user()->id }} data-price="2499">Buy</button></li>
                        </ul>
                    </div>
                    <div class="card mt-5">
                        <ul class="mt-4">
                            <li class="pack">Platinum (Paid)</li>
                            <li id="professional" class="price bottom-bar">9999.00 INR</li>
                            <li class="bottom-bar">Premium features compared to the Silver and Gold tiers</li>
                            <li><button class="buy-btn btn btn-success" id='Platinum' data-id={{ Auth::user()->id }} data-price="2499">Buy</button></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Payment History Table Section -->
            <div class="col-md-6 mb-4">
                <h3>Subscription Plan</h3>
                <table class="table table-border text-white">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Subscription</th>
                            <th>status</th>
                            <th>Subscription Start Date</th>
                            <th>Subscription Start End</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentStatus as $order): ?>
                        <tr>
                            <td><?= $order->payment_id; ?></td>
                            <td><?= $order->order_id; ?></td>
                            <td><?= $order->amount; ?></td>
                            <td>
                                @if ($order->amount == 0)
                                    Silver
                                @elseif ($order->amount == 2499)
                                    Gold
                                @elseif ($order->amount == 9999)
                                    Platinum
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td><?= $order->status; ?></td>
                            <td><?= $order->created_at->format('d M Y'); ?></td>
                            <td><?= $order->created_at->addMonth()->format('d M Y'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        console.clear();
        function generateOrderId() {
        const timestamp = Date.now().toString(); // Current time in milliseconds
        const randomString = Math.random().toString(36).substring(2, 6).toUpperCase(); // Generate a random 4-character string
        return randomString + timestamp.slice(-6); // Combine random string with the last 6 digits of timestamp
    }
        // JavaScript code for Razorpay integration
        document.querySelectorAll('.buy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const priceId = this.id;
                let amount;

                // Define amounts based on the plan selected
                if (priceId === 'Silver') {
                    amount = 0; // Free tier
                } else if (priceId === 'Gold') {
                    amount = 2499; // Gold tier price
                } else if (priceId === 'Platinum') {
                    amount = 9999; // Platinum tier price
                }

                // Multiply price by 100 to convert to paise (Razorpay expects paise)
                const amountInPaise = amount * 100;

                // Only proceed with Razorpay if price is greater than zero
                if (amount > 0) {
                    // Create a Razorpay order here
                    fetch('/create-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ userId, price: amountInPaise })
                    })
                    .then(response => response.json())
                    .then(order => {
                        const options = {
                            key: '{{ env("RAZORPAY_KEY") }}', // Enter the Key ID generated from the Dashboard
                            amount: amountInPaise, // Amount is in currency subunits (paise)
                            currency: 'INR',
                            name: 'Raj group',
                            description: priceId,
                            order_id:order.id,
                            handler: function (response) {
                                // Handle payment success
                                fetch('/payment-callback', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        order_id: generateOrderId(),
                                        payment_id: response.razorpay_payment_id,
                                        userId: userId,
                                        amount: amount
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        // Redirect to the success route
                                        window.location.href = '{{ route("payment.success") }}';
                                    } else {
                                        window.location.href = '{{ route("payment.failure") }}';
                                    }
                                });
                            },
                            prefill: {
                                name: '{{ Auth::user()->name }}', // Replace with user's name
                                email: '{{ Auth::user()->email }}' // Replace with user's email
                            },
                            notes: {
                                address: 'Sample Address' // Replace with user address if needed
                            },
                            theme: {
                                color: '#F37254' // Custom theme color
                            }
                        };
                        const razorpay = new Razorpay(options);
                        razorpay.open();
                    });
                } else {
                    alert('This is a free plan. No payment required.');
                    // Proceed with free plan logic here
                }
            });
        });
    </script>
</body>
</html>

