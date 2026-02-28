<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TenantNotFoundException extends NotFoundHttpException
{
    public static function forSlug(string $slug): self
    {
        return new self("Tenant not found for slug [{$slug}].");
    }
}
