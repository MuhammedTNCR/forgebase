<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_sees_their_workspaces_on_central_host(): void
    {
        $user = User::factory()->create();
        $acme = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme']);
        $globex = Tenant::query()->create(['name' => 'Globex', 'slug' => 'globex']);

        $user->tenants()->attach($acme->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->get($this->centralUrl('/workspaces'));

        $response
            ->assertOk()
            ->assertSee('Acme')
            ->assertDontSee('Globex');
    }

    public function test_selecting_a_tenant_redirects_to_tenant_subdomain(): void
    {
        $user = User::factory()->create();
        $acme = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme']);
        $user->tenants()->attach($acme->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->post($this->centralUrl("/workspaces/{$acme->id}/select"));

        $response->assertRedirect(rtrim($this->tenantUrl('acme'), '/'));
    }

    public function test_selecting_tenant_without_membership_returns_forbidden(): void
    {
        $user = User::factory()->create();
        $globex = Tenant::query()->create(['name' => 'Globex', 'slug' => 'globex']);

        $response = $this->actingAs($user)->post($this->centralUrl("/workspaces/{$globex->id}/select"));

        $response->assertForbidden();
    }
}
