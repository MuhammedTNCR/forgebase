<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TenantRequiredException extends NotFoundHttpException
{
    public static function forRoute(): self
    {
        return new self('Tenant context is required for this route.');
    }
}
