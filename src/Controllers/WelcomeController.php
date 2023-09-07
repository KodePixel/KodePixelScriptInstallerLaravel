<?php

namespace Kode\PixelPayload\Controllers;

use Illuminate\Routing\Controller;
use Kode\PixelPayload\Helpers\EnvironmentManager;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        $this->getEnvContent;
        $server = appServerUrl(url('/'));
        return view('pdo::welcome',compact('server'));
    }
}
