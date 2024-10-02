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
            background: #1e1e2f;
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
            color: #ffffff;
            display: flex;
            flex-direction: column;
        }

        /* Top Menu Bar */
        nav {
            background-color: #282c34;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        nav h1 {
            font-size: 1.8rem;
            color: #ffffff;
            letter-spacing: 1px;
        }

        nav .user-info {
            display: flex;
            align-items: center;
        }

        nav .user-info span {
            margin-right: 1.5rem;
            font-weight: bold;
            color: #cfcfcf;
        }

        nav .logout-btn {
            padding: 0.5rem 1.2rem;
            background-color: #ff4c4c;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 0.9rem;
        }

        nav .logout-btn:hover {
            background-color: #ff1f1f;
            transform: translateY(-2px);
        }

        /* Main content */
        main {
            margin-top: 90px;
            width: 80%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-top: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .container {
            background-color: #2c2f3f;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
        }

        h1 {
            font-size: 2.2rem;
            color: #00e676;
            margin-bottom: 1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        h1::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: #00e676;
            bottom: 0;
            left: -100%;
            animation: slide-in 1s forwards;
        }

        @keyframes slide-in {
            100% {
                left: 0;
            }
        }

        p {
            font-size: 1.2rem;
            color: #f1f1f1;
            margin-top: 0.5rem;
            margin-bottom: 36px;
        }

        /* Button Style */
        .container .btn {
            margin-top: 1.5rem;
            padding: 0.8rem 2rem;
            background-color: #00e676;
            color: #1e1e2f;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .container .btn:hover {
            background-color: #00c85e;
            transform: translateY(-3px);
        }

        /* Media Queries */
        @media (max-width: 768px) {
            main {
                width: 90%;
            }

            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            main {
                flex-direction: column;
            }
        }
        .custom-padding {
            padding: 5px 10px; /* Adjust values as needed */
        }
    </style>
</head>
<body>
    <!-- Top Menu Bar -->
    <nav>
        <h1><a href="<?php echo e(route('index')); ?>" class="text-white" style="text-decoration: none;">Home</a></h1>
        <div class="user-info">
            <span class="bg-success custom-padding" style="text-transform: uppercase;">
                Active - <?php echo e(!empty($Subscription->switch) ? $Subscription->switch : (!empty($Subscription->tier) ? $Subscription->tier : 'No Plan')); ?>

            </span>
            <span class="bg-warning custom-padding text-white"><a href="<?php echo e(route('subscriptions.index')); ?>" class="text-mute text-white" style="text-decoration: none;">Upgrade/Downgrade</a></span>
            <span><?php echo e(Auth::user()->name); ?></span>
            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>
    <!-- Main Content -->
    <main>
        <div class="container">
            <h1>Manage Subscription</h1>
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <form id="subscription-form" action="<?php echo e(route('subscriptions.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="old_tier" value="<?php echo e($Subscription->tier); ?>">
                <div class="form-group">
                    <label for="tier">Select Subscription Tier</label>
                    <select name="tier" id="tier" class="form-control" required>
                        <option value="#">Select Subscription Tier</option>
                        <option value="silver" <?php if($Subscription->switch === 'silver'): ?> disabled <?php endif; ?>>Silver</option>
                        <option value="gold" <?php if($Subscription->switch === 'gold'): ?> disabled <?php endif; ?>>Gold</option>
                        <option value="platinum" <?php if($Subscription->switch === 'platinum'): ?> disabled <?php endif; ?>>Platinum</option>
                    </select>
                </div>
                <button type="submit" id='Upgrade' class="btn btn-primary">Submit</button>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const currentTier = '<?php echo e($Subscription->tier); ?>'; // Get current tier from PHP
            const tiers = ['silver', 'gold', 'platinum']; // Define the tiers in order

            $('#subscription-form').on('submit', function(e) {
                const selectedTier = $('#tier').val();

                // Check if the selected tier is greater than the current tier
                if (tiers.indexOf(selectedTier) > tiers.indexOf(currentTier)) {
                    e.preventDefault(); // Prevent form submission
                    if (confirm("You cannot select a tier greater than your current plan. Would you like to buy a plan?")) {
                    window.location.href = "<?php echo e(route('index')); ?>"; // Replace 'buy.plan' with the correct route name
                    }
                } else {
                    // Show confirmation alert for valid changes
                    alert(`You are switching from ${currentTier.toUpperCase()} to ${selectedTier.toUpperCase()}.`);
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\SaaS_Subscription\resources\views/subscriptions/index.blade.php ENDPATH**/ ?>