<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ProjectCreated;
use App\Events\ProjectDeleted;
use App\Events\ProjectUpdated;
use App\Events\TenantInvitationAccepted;
use App\Events\TenantInvitationCreated;
use App\Listeners\LogInvitationActivity;
use App\Listeners\LogProjectActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, list<class-string>> */
    protected $listen = [
        ProjectCreated::class => [
            LogProjectActivity::class,
        ],
        ProjectUpdated::class => [
            LogProjectActivity::class,
        ],
        ProjectDeleted::class => [
            LogProjectActivity::class,
        ],
        TenantInvitationCreated::class => [
            LogInvitationActivity::class,
        ],
        TenantInvitationAccepted::class => [
            LogInvitationActivity::class,
        ],
    ];
}
