<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
   public function dashboard()
    {
        // Fetch all orders
        $orders = \App\Models\Order::with('customer', 'driver')->orderBy('created_at', 'desc')->get();
        // Fetch all users who have the 'driver' role
        $drivers = \App\Models\User::where('role', 'driver')->get(); 
        
        return view('admin', compact('orders', 'drivers'));
    }

    public function updateStatus(\Illuminate\Http\Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $order->update([
            'status' => $request->status,
            'driver_id' => $request->driver_id // Saves the specific driver assigned!
        ]);
        
        return back()->with('success', 'Order and Driver assignment updated!');
    }

    public function addDriver(\Illuminate\Http\Request $request)
    {
        // 1. Validate the driver's info
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // 2. Create the user and FORCE the 'driver' role
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'driver' // Only an admin can trigger this code!
        ]);

        // 3. Send the admin back to the dashboard with a success message
        return back()->with('success', 'New Delivery Driver added successfully!');
    }
}