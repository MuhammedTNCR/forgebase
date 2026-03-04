<?php

declare(strict_types=1);

return [
    'default_plan' => 'free',

    'plans' => [
        'free' => [
            'projects',
        ],
        'pro' => [
            'projects',
            'activity_log',
            'team_invites',
        ],
        'enterprise' => [
            'projects',
            'activity_log',
            'team_invites',
            'advanced_rbac',
            'export_csv',
        ],
    ],
];
