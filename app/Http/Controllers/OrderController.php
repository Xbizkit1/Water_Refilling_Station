<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'order_type' => 'required|in:new_gallon,swap_gallon,refill_gallon',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string'
        ]);

        // 2. Find the user by email, OR create a new user in the SQL database
        $user = User::firstOrCreate(
            ['email' => $request->customer_email], // What to search for
            [
                'name' => $request->customer_name, // Data to use if creating a new user
                'role' => 'customer',
                'password' => Hash::make('default_password_123') // Required by the DB
            ]
        );

        // 3. Pricing logic
        $prices = [
            'new_gallon' => 250.00,
            'swap_gallon' => 40.00,
            'refill_gallon' => 35.00
        ];

        // 4. Create the order and link the real user ID
       Order::create([
            'customer_id' => Auth::id(), // Now officially linked to the SQL users table!
            'order_type' => $request->order_type,
            'status' => 'pending',
            'payment_method' => 'COD',
            'quantity' => $request->quantity,
            'total_amount' => $prices[$request->order_type] * $request->quantity,
            'address' => $request->address
        ]);

        return back()->with('success', 'Order placed successfully! Pay via COD upon delivery.');
    }
}