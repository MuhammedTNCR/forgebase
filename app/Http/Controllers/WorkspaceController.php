<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Support\Tenancy\TenancyUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $tenants = $request->user()
            ->tenants()
            ->orderBy('name')
            ->get();

        return view('workspaces.index', compact('tenants'));
    }

    public function select(Request $request, Tenant $tenant): RedirectResponse
    {
        abort_unless(
            $request->user()->tenants()->whereKey($tenant->getKey())->exists(),
            403
        );

        return redirect()->away(TenancyUrl::tenantUrl($tenant));
    }
}
