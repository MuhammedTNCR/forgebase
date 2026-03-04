<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

class ProjectDeleted
{
    use Dispatchable, InteractsWithSockets;

    /** @param array<string, mixed> $properties */
    public function __construct(
        public Project $project,
        public ?Model $actor,
        public ?int $tenantId,
        public array $properties = [],
    ) {
    }
}
