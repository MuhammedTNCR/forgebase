<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TenantInvitationAccepted;
use App\Events\TenantInvitationCreated;
use App\Support\Activity\ActivityLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class LogInvitationActivity implements ShouldQueue, ShouldQueueAfterCommit
{
    public function handle(TenantInvitationCreated|TenantInvitationAccepted $event): void
    {
        $action = match ($event::class) {
            TenantInvitationCreated::class => 'team.invited',
            TenantInvitationAccepted::class => 'team.invite_accepted',
        };

        app(ActivityLogger::class)->log(
            $action,
            null,
            $event->properties,
            $event->actor,
            $event->tenantId,
        );
    }
}
