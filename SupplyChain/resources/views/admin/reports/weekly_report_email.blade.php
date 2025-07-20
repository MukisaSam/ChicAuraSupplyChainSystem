<div style="font-family: Arial, sans-serif;">
    <h2>Weekly Admin Report</h2>
    <p><strong>Reporting Period:</strong> {{ $period_start->toDayDateTimeString() }} - {{ $period_end->toDayDateTimeString() }}</p>

    <h3>Sales Summary</h3>
    <ul>
        <li><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }}</li>
        <li><strong>Number of Sales:</strong> {{ $salesCount }}</li>
    </ul>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->toDayDateTimeString() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>User Activity</h3>
    <ul>
        <li><strong>New Users:</strong> {{ $newUsersCount }}</li>
        <li><strong>Active Wholesalers (placed orders):</strong> {{ $activeWholesalers }}</li>
        <li><strong>Active Manufacturers (placed orders):</strong> {{ $activeManufacturers }}</li>
    </ul>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->toDayDateTimeString() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Inventory Summary</h3>
    <ul>
        <li><strong>Total Inventory Items:</strong> {{ $totalInventory }}</li>
        <li><strong>Low Stock Items (&lt; 10):</strong> {{ $lowStockItems->count() }}</li>
    </ul>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> 