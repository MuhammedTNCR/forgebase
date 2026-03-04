<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Project;
use App\Events\ProjectCreated;
use App\Events\ProjectDeleted;
use App\Events\ProjectUpdated;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProjectObserver
{
    public function created(Project $project): void
    {
        DB::afterCommit(function () use ($project): void {
            event(new ProjectCreated($project, auth()->user(), $project->tenant_id));
        });
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

        DB::afterCommit(function () use ($project, $changes, $before): void {
            event(new ProjectUpdated($project, auth()->user(), $project->tenant_id, [
                'changes' => $changes,
                'before' => $before,
            ]));
        });
    }

    public function deleted(Project $project): void
    {
        DB::afterCommit(function () use ($project): void {
            event(new ProjectDeleted($project, auth()->user(), $project->tenant_id));
        });
    }
}
