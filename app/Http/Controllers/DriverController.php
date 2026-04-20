<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;

class DriverController extends Controller
{
    public function dashboard()
    {
        // Find orders assigned to the logged-in driver that are NOT delivered yet
        $orders = \App\Models\Order::with('customer')
                    ->where('driver_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('status', '!=', 'delivered')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('driver', compact('orders'));
    }

    public function uploadProof(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'proof_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Ensure this driver actually owns this order
        $order = \App\Models\Order::where('id', $id)->where('driver_id', \Illuminate\Support\Facades\Auth::id())->firstOrFail();

        if ($request->hasFile('proof_photo')) {
            $path = $request->file('proof_photo')->store('proofs', 'public');
            $order->update([
                'proof_photo' => $path,
                'status' => 'delivered' // Automatically updates status to delivered!
            ]);
        }

        return back()->with('success', 'Delivery completed and proof uploaded!');
    }
}