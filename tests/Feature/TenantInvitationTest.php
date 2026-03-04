<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\TenantInvitationMail;
use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TenantInvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_owner_can_create_invitation(): void
    {
        Mail::fake();

        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $owner = User::factory()->create();
        $owner->tenants()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($owner)->post($this->centralUrl("/workspaces/{$tenant->id}/invitations"), [
            'email' => 'invitee@example.com',
            'role' => 'member',
        ]);

        $response->assertRedirect($this->centralUrl("/workspaces/{$tenant->id}/team"));

        $this->assertDatabaseHas('tenant_invitations', [
            'tenant_id' => $tenant->id,
            'email' => 'invitee@example.com',
            'role' => 'member',
        ]);

        Mail::assertSent(TenantInvitationMail::class, function (TenantInvitationMail $mail) {
            $html = $mail->render();

            return str_contains($html, 'signature=') && str_contains($html, 'invitations/accept');
        });
    }

    public function test_member_cannot_create_invitation(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
            'plan' => 'pro',
        ]);

        $member = User::factory()->create();
        $member->tenants()->attach($tenant->id, ['role' => 'member']);

        $response = $this->actingAs($member)->post($this->centralUrl("/workspaces/{$tenant->id}/invitations"), [
            'email' => 'invitee@example.com',
            'role' => 'member',
        ]);

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_before_accepting(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => 'guest@example.com',
            'role' => 'member',
            'token' => 'token-guest',
            'expires_at' => now()->addDays(7),
        ]);

        $acceptUrl = $this->signedAcceptUrl($invitation->token, $invitation->expires_at);

        $response = $this->get($acceptUrl);

        $response->assertRedirect($this->centralUrl('/login'));
    }

    public function test_accepting_invitation_attaches_membership_and_marks_accepted(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $user = User::factory()->create([
            'email' => 'invitee@example.com',
        ]);

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => 'invitee@example.com',
            'role' => 'admin',
            'token' => 'token-accept',
            'expires_at' => now()->addDays(7),
        ]);

        $acceptUrl = $this->signedAcceptUrl($invitation->token, $invitation->expires_at);

        $response = $this->actingAs($user)->get($acceptUrl);

        $response->assertRedirect($this->tenantUrl('acme', '/projects'));

        $this->assertDatabaseHas('tenant_user', [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('tenant_invitations', [
            'id' => $invitation->id,
            'accepted_by_user_id' => $user->id,
        ]);
    }

    public function test_expired_invitation_cannot_be_accepted(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $user = User::factory()->create([
            'email' => 'invitee@example.com',
        ]);

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => 'invitee@example.com',
            'role' => 'member',
            'token' => 'token-expired',
            'expires_at' => now()->subDay(),
        ]);

        $acceptUrl = $this->signedAcceptUrl($invitation->token, now()->addDays(7));

        $response = $this->actingAs($user)->get($acceptUrl);

        $response->assertForbidden();
    }

    public function test_accepting_with_mismatched_email_is_denied(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $user = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => 'invitee@example.com',
            'role' => 'member',
            'token' => 'token-mismatch',
            'expires_at' => now()->addDays(7),
        ]);

        $acceptUrl = $this->signedAcceptUrl($invitation->token, $invitation->expires_at);

        $response = $this->actingAs($user)->get($acceptUrl);

        $response->assertForbidden();
    }

    public function test_accepting_twice_is_idempotent(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $user = User::factory()->create([
            'email' => 'invitee@example.com',
        ]);

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => 'invitee@example.com',
            'role' => 'member',
            'token' => 'token-idempotent',
            'expires_at' => now()->addDays(7),
        ]);

        $acceptUrl = $this->signedAcceptUrl($invitation->token, $invitation->expires_at);

        $this->actingAs($user)->get($acceptUrl)->assertRedirect($this->tenantUrl('acme', '/projects'));
        $this->actingAs($user)->get($acceptUrl)->assertRedirect($this->tenantUrl('acme', '/projects'));

        $this->assertDatabaseCount('tenant_user', 1);
    }

    protected function signedAcceptUrl(string $token, $expiresAt): string
    {
        $root = rtrim($this->centralUrl('/'), '/');
        URL::forceRootUrl($root);

        return URL::temporarySignedRoute('invitations.accept', $expiresAt, ['token' => $token]);
    }
}
