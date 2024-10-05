Laravel Subscription Management System
Overview
This project is a Subscription Management System for a Software as a Service (SaaS) platform built using Laravel 10 and MySQL. The system supports three subscription tiers: Silver (Free), Gold (Paid), and Platinum (Paid). It integrates with the Razorpay Payment Gateway for processing paid subscriptions.

Subscription Tiers:
Silver (Free):
Free-tier access with limited features.
Gold (Paid):
Subscription cost: 2499.00 INR
Enhanced features compared to the Silver tier.
Payment via Razorpay.
Platinum (Paid):
Subscription cost: 9999.00 INR
Premium features compared to both Silver and Gold tiers.
Payment via Razorpay.
Features
User Authentication: Utilizes Laravel's built-in authentication system.
Subscription Management:
View available subscription tiers (Silver, Gold, Platinum).
Subscribe to a plan or upgrade/downgrade between plans.
Handle payments using Razorpay for Gold and Platinum subscriptions.
Authorization: Ensures only authenticated users can manage subscriptions.
Error Handling: Displays appropriate messages for payment failures and subscription errors.
Scalable and Secure: Implements proper security practices, especially for payment handling.
Requirements
System Requirements:
PHP 8.x
Laravel 10.x
MySQL 5.x/8.x
Composer
Razorpay Account
Laravel Packages:
laravel/ui: For user authentication scaffolding.
razorpay/razorpay: Razorpay PHP SDK for payment integration.


**You can create the database schema using Laravel migrations:**
php artisan migrate


 **Install Dependencies**
 composer install


**Generate Application Key**
php artisan key:generate

**Run Migrations**
php artisan migrate

**Run the Application**
php artisan serve




