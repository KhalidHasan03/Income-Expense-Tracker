<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #18181b;
        }

        h1 {
            font-size: 18px;
            margin: 0
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px
        }

        th,
        td {
            border: 1px solid #e4e4e7;
            padding: 6px 8px;
            text-align: left
        }

        th {
            background: #f4f4f5
        }

        .right {
            text-align: right
        }

        .emerald {
            color: #059669
        }

        .red {
            color: #dc2626
        }

        .header {
            border-bottom: 2px solid #18181b;
            padding-bottom: 8px;
            margin-bottom: 12px
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Expense Tracker — Report</h1>
        <div>{{ \Carbon\Carbon::parse($from)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($to)->format('M d, Y') }} • Income ${{ number_format($income,2) }} • Expense ${{ number_format($expense,2) }} • Balance ${{ number_format($income-$expense,2) }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Category</th>
                <th>Type</th>
                <th class="right">Amount</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $t)<tr>
                <td>{{ $t->transacted_at->format('Y-m-d') }}</td>
                <td>{{ $t->title }}</td>
                <td>{{ $t->category->name ?? '-' }}</td>
                <td>{{ $t->type }}</td>
                <td class="right {{ $t->type=='income'?'emerald':'red' }}">{{ $t->type=='income'?'+':'-' }}${{ number_format($t->amount,2) }}</td>
                <td>{{ $t->note }}</td>
            </tr>@empty<tr>
                <td colspan="6" style="text-align:center">No records</td>
            </tr>@endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;font-size:10px;color:#71717a">Generated {{ now()->format('Y-m-d H:i') }} • {{ $data->count() }} records • Ordered newest first (DESC)</div>
</body>

</html>
