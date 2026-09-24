<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <h2>Update on Your Claim</h2>
    <p>Your ownership claim on the item below was not approved by the finder.</p>

    <table style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Item</strong></td>
            <td style="padding: 4px 0;">{{ $claim->item->title }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Status</strong></td>
            <td style="padding: 4px 0;">Rejected</td>
        </tr>
    </table>

    @if ($claim->response_notes)
        <p><strong>Reason provided:</strong></p>
        <blockquote style="border-left: 3px solid #ccc; margin: 8px 0; padding-left: 12px; color: #555;">
            {{ $claim->response_notes }}
        </blockquote>
    @endif

    <p>If you believe this was a mistake, you can view the item and reach out to the finder directly, or submit a new claim with more supporting evidence.</p>

    <p>
        <a href="{{ route('items.show', $claim->item) }}"
           style="display: inline-block; padding: 10px 20px; background: #0d9488; color: #fff; text-decoration: none; border-radius: 6px;">
            View Item
        </a>
    </p>

    <p style="margin-top: 24px; color: #999; font-size: 12px;">
        {{ config('app.name') }}
    </p>
</body>
</html>