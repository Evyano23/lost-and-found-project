<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <h2>New Report Submitted</h2>
    <p>A new report has been filed and is awaiting admin review.</p>

    <table style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Report ID</strong></td>
            <td style="padding: 4px 0;">#{{ $report->id }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Type</strong></td>
            <td style="padding: 4px 0;">{{ $report->type->label() }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Status</strong></td>
            <td style="padding: 4px 0;">{{ $report->status->value }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Reported By</strong></td>
            <td style="padding: 4px 0;">{{ $report->reporter?->name ?? 'Unknown' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Submitted</strong></td>
            <td style="padding: 4px 0;">{{ $report->created_at->format('M d, Y H:i') }}</td>
        </tr>
    </table>

    @if ($report->reason)
        <p><strong>Reason provided:</strong></p>
        <blockquote style="border-left: 3px solid #ccc; margin: 8px 0; padding-left: 12px; color: #555;">
            {{ $report->reason }}
        </blockquote>
    @endif

    <p>
        <a href="{{ url('/admin/reports/' . $report->id) }}"
           style="display: inline-block; padding: 10px 20px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;">
            Review Report
        </a>
    </p>

    <p style="margin-top: 24px; color: #999; font-size: 12px;">
        {{ config('app.name') }}
    </p>
</body>
</html>
