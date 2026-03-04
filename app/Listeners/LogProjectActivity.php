<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Events\ProjectDeleted;
use App\Events\ProjectUpdated;
use App\Support\Activity\ActivityLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class LogProjectActivity implements ShouldQueue, ShouldQueueAfterCommit
{
    public function handle(ProjectCreated|ProjectUpdated|ProjectDeleted $event): void
    {
        $action = match ($event::class) {
            ProjectCreated::class => 'project.created',
            ProjectUpdated::class => 'project.updated',
            ProjectDeleted::class => 'project.deleted',
        };

        app(ActivityLogger::class)->log(
            $action,
            $event->project,
            $event->properties,
            $event->actor,
            $event->tenantId,
        );
    }
}
