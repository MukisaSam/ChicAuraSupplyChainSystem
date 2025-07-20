<div style="font-family: Arial, sans-serif;">
    <h2>Weekly Supplier Report</h2>
    <p><strong>Supplier:</strong> {{ $supplier->name }} ({{ $supplier->email }})</p>
    <p><strong>Reporting Period:</strong> {{ $period_start->toDayDateTimeString() }} - {{ $period_end->toDayDateTimeString() }}</p>

    <h3>Sales/Orders Summary</h3>
    <ul>
        <li><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }}</li>
        <li><strong>Number of Orders:</strong> {{ $salesCount }}</li>
    </ul>
    <table border="1" cellpadding="5" cellspacing="0" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->created_at->toDayDateTimeString() }}</td>
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
            @foreach($suppliedItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4>Low Stock Items (&lt; 10)</h4>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No low stock items.</td></tr>
            @endforelse
        </tbody>
    </table>
</div> 