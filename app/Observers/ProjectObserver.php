<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Project;
use App\Support\Activity\ActivityLogger;
use Illuminate\Support\Arr;

class ProjectObserver
{
    public function created(Project $project): void
    {
        app(ActivityLogger::class)->log('project.created', $project);
    }

    public function updated(Project $project): void
    {
        $changes = Arr::except($project->getChanges(), ['updated_at']);

        if ($changes === []) {
            return;
        }

        $before = [];
        foreach (array_keys($changes) as $key) {
            $before[$key] = $project->getOriginal($key);
        }

        app(ActivityLogger::class)->log('project.updated', $project, [
            'changes' => $changes,
            'before' => $before,
        ]);
    }

    public function deleted(Project $project): void
    {
        app(ActivityLogger::class)->log('project.deleted', $project);
    }
}
