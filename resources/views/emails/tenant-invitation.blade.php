@php
    $formattedExpires = $expiresAt?->toDayDateTimeString();
@endphp

<p>Hello,</p>

<p>
    You have been invited to join <strong>{{ $tenantName }}</strong>
    as a <strong>{{ ucfirst($role) }}</strong>.
</p>

@if ($inviterName)
    <p>Invited by: {{ $inviterName }}</p>
@endif

<p>
    <a href="{{ $acceptUrl }}">Accept invitation</a>
</p>

@if ($formattedExpires)
    <p>This invitation expires on {{ $formattedExpires }}.</p>
@endif

<p>If you did not expect this invitation, you can ignore this email.</p>
