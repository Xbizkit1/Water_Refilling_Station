<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Water Station</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            color: #343a40;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #343a40;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-delivered {
            background-color: #28a745;
            color: #ffffff;
        }

        select {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .btn {
            background-color: #17a2b8;
            color: #ffffff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #138496;
        }

        .photo-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Dashboard - Live Orders</h2>

        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 25px;">
            <h3 style="margin-top: 0; color: #343a40; font-size: 18px;">+ Add New Delivery Driver</h3>
            
            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.driver.add') }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                @csrf
                <div style="flex: 1; min-width: 200px;">
                    <label style="display:block; font-weight: bold; font-size: 14px; margin-bottom: 5px;">Driver Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display:block; font-weight: bold; font-size: 14px; margin-bottom: 5px;">Email</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display:block; font-weight: bold; font-size: 14px; margin-bottom: 5px;">Temporary Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <button type="submit" class="btn" style="background-color: #28a745; padding: 10px 20px;">Create Driver</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>Total (COD)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $order->order_type)) }}</td>
                    <td>{{ $order->address }}</td>
                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->status == 'delivered' ? 'badge-delivered' : 'badge-pending' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                        @if($order->proof_photo)
                            <br><a href="{{ asset('storage/' . $order->proof_photo) }}" target="_blank" class="photo-link" style="display:inline-block; margin-top:5px;">View Proof</a>
                        @endif
                    </td>
                    <td style="min-width: 250px;">
                        <form action="/admin/order/{{ $order->id }}/update" method="POST" style="display:flex; flex-direction:column; gap:5px;">
                            @csrf
                            <select name="status" style="padding: 5px; border: 1px solid #ced4da; border-radius: 4px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ $order->status == 'assigned' ? 'selected' : '' }}>Assigned to Driver</option>
                                <option value="picked_up" {{ $order->status == 'picked_up' ? 'selected' : '' }}>Picked Up (Refill)</option>
                            </select>
                            
                            <select name="driver_id" style="padding: 5px; border: 1px solid #ced4da; border-radius: 4px;">
                                <option value="">-- Select Driver --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn" style="padding: 5px;">Save Updates</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 20px;">No orders found in the database yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>