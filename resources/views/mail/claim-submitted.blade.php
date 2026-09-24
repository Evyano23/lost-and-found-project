<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <h2>Someone Has Claimed Your Item</h2>
    <p>A claim has been submitted for an item you reported. Please review the details and verify ownership before releasing it.</p>

    <table style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Item</strong></td>
            <td style="padding: 4px 0;">{{ $claim->item->title }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Claimant</strong></td>
            <td style="padding: 4px 0;">{{ $claim->user->name }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Contact Phone</strong></td>
            <td style="padding: 4px 0;">{{ $claim->contact_phone ?? 'Not provided' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Contact Email</strong></td>
            <td style="padding: 4px 0;">{{ $claim->contact_email ?? 'Not provided' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #666;"><strong>Submitted</strong></td>
            <td style="padding: 4px 0;">{{ $claim->created_at->format('M d, Y H:i') }}</td>
        </tr>
    </table>

    <p><strong>Proof of ownership provided:</strong></p>
    <blockquote style="border-left: 3px solid #ccc; margin: 8px 0; padding-left: 12px; color: #555;">
        {{ $claim->proof_description }}
    </blockquote>

    <p>
        <a href="{{ route('claims.item-claims', $claim->item) }}"
           style="display: inline-block; padding: 10px 20px; background: #0d9488; color: #fff; text-decoration: none; border-radius: 6px;">
            Review Claim
        </a>
    </p>

    <p style="margin-top: 24px; color: #999; font-size: 12px;">
        {{ config('app.name') }}
    </p>
</body>
</html>