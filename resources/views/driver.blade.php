<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #343a40;
            color: #ffffff;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #495057;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        h2 {
            border-bottom: 2px solid #6c757d;
            padding-bottom: 10px;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            color: #000000;
            border-radius: 4px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .btn {
            background-color: #28a745;
            color: #ffffff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .btn:hover {
            background-color: #218838;
        }

        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        input[type="file"] {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Driver Portal - Active Deliveries</h2>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Logout</button>
            </form>
        </div>

        <p>Welcome back, <strong>{{ Auth::user()->name }}</strong>!</p>

        @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Order Type & COD</th>
                    <th>Action (Upload Proof)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->customer->name ?? 'Guest' }}</strong></td>
                    <td>{{ $order->address }}</td>
                    <td>
                        {{ ucwords(str_replace('_', ' ', $order->order_type)) }}<br>
                        <span style="color: #dc3545; font-weight: bold;">Collect: ₱{{ number_format($order->total_amount, 2) }}</span>
                    </td>
                    <td>
                        <form action="/driver/order/{{ $order->id }}/proof" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:8px;">
                            @csrf
                            <input type="file" name="proof_photo" accept="image/*" required>
                            <button type="submit" class="btn">Mark Delivered</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 30px;">You have no active deliveries assigned right now.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>