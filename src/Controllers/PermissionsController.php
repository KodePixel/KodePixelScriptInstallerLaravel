<?php

namespace Kode\PixelPayload\Controllers;

use Illuminate\Routing\Controller;
use Kode\PixelPayload\Helpers\PermissionsChecker;

class PermissionsController extends Controller
{
    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $permissions = $this->permissions->check(
            config('requirements.permissions')
        );

        return view('pdo::permissions', compact('permissions'));
    }
}
