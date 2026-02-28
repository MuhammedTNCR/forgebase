<?php

namespace App\Support\Tenancy;

class TenantContext
{
    protected mixed $tenant = null;

    public function set(mixed $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): mixed
    {
        return $this->tenant;
    }

    public function has(): bool
    {
        return $this->tenant !== null;
    }

    public function id(): mixed
    {
        if (! $this->has()) {
            return null;
        }

        if (is_object($this->tenant) && isset($this->tenant->id)) {
            return $this->tenant->id;
        }

        if (is_array($this->tenant) && array_key_exists('id', $this->tenant)) {
            return $this->tenant['id'];
        }

        return $this->tenant;
    }

    public function clear(): void
    {
        $this->tenant = null;
    }
}
