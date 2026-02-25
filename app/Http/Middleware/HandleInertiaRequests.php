<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'Permission' => [
                    'user_roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
                    'user_permision' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
                ]
            ],
            'Permission' => [
                'user_roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
                'user_permision' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            ],
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'success' => fn() => $request->session()->get('success'),
            ],
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
                'route_name' => $request->route() ? $request->route()->getName() : null,
            ],
        ]);
    }
}
