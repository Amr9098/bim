<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIM Monthly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .transaction-container {
            margin-top: 40px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .transaction-header {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .payment-table {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>BIM Monthly Report</h1>

    @forelse($monthlyReport as $report)
        <div class="transaction-container">
            <div class="transaction-header">Transaction ID: {{ $report->id }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Paid</th>
                        <th>Outstanding</th>
                        <th>Overdue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->due_on)->format('F') }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->due_on)->format('Y') }}</td>
                        <td>{{ $report->paid }}</td>
                        <td>{{ $report->outstanding }}</td>
                        <td>{{ $report->overdue }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment details -->
            <div class="transaction-header">Payment Details</div>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Paid On</th>
                        <th>Details</th>
                        <th>User ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->paid_on }}</td>
                            <td>{{ $payment->details }}</td>
                            <td>{{ $payment->user_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No payment details available for this transaction.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <p>No transactions found for the specified date range.</p>
    @endforelse
</body>
</html>
