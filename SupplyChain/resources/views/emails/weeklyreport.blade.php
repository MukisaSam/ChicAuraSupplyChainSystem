<h2>User Activity Report – Last 7 Days</h2>

<ul>
    <li><strong>New Users:</strong> {{ $reportData['newUsersCount'] }}</li>
    <li><strong>Active Users (placed orders):</strong> {{ $reportData['activeUsersCount'] }}</li>
</ul>

<hr>

<h3>🆕 New Users</h3>
<ul>
    @foreach($reportData['newUsers'] as $user)
        <li>{{ $user->name }} ({{ $user->email }}) – Joined on {{ $user->created_at->format('M d, Y') }}</li>
    @endforeach
</ul>

<hr>

<h3>🔥 Active Users (with orders)</h3>
<ul>
    @foreach($reportData['activeUsers'] as $user)
        <li>{{ $user->name }} ({{ $user->email }}) – Orders: {{ $user->orders_count }}</li>
    @endforeach
</ul>

