<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Refill Station - Order Now</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0056b3;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        select,
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Place an Order (Cash on Delivery)</h2>

        <div style="background-color: #e9ecef; padding: 15px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Ordering as:</strong> {{ Auth::user()->name }} <br>
                <span style="font-size: 12px; color: #666;">(Customer Account)</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Logout</button>
            </form>
        </div>


        @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
        @endif

        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <hr style="border: 1px solid #e9ecef; margin: 20px 0;">

            <div class="form-group">
                <label>Order Type</label>
                <select name="order_type" required>
                    <option value="new_gallon">Buy New 25L Gallon + Free Water (₱250)</option>
                    <option value="swap_gallon">Swap Empty Gallon for Full (₱40)</option>
                    <option value="refill_gallon">Request Refill - Pickup & Return (₱35)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Delivery Address</label>
                <input type="text" name="address" placeholder="e.g., 123 Main St, Block 4" required>
            </div>
            <button type="submit" class="btn">Confirm Order</button>
        </form>