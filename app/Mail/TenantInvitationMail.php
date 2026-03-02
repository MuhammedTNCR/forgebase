<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class TenantInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public TenantInvitation $invitation,
        public ?User $inviter,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join '.$this->tenant->name,
        );
    }

    public function content(): Content
    {
        $expiresAt = $this->invitation->expires_at ?? now()->addDays(7);

        $acceptUrl = URL::temporarySignedRoute(
            'invitations.accept',
            $expiresAt,
            ['token' => $this->invitation->token]
        );

        return new Content(
            view: 'emails.tenant-invitation',
            with: [
                'tenantName' => $this->tenant->name,
                'role' => $this->invitation->role,
                'inviterName' => $this->inviter?->name,
                'acceptUrl' => $acceptUrl,
                'expiresAt' => $expiresAt,
            ],
        );
    }
}
