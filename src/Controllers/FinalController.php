<?php

namespace Kode\PixelPayload\Controllers;

use Illuminate\Routing\Controller;
use Kode\PixelPayload\Events\LaravelInstallerFinished;
use Kode\PixelPayload\Helpers\EnvironmentManager;
use Kode\PixelPayload\Helpers\FinalInstallManager;
use Kode\PixelPayload\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Kode\PixelPayload\\Helpers\InstalledFileManager $fileManager
     * @param \Kode\PixelPayload\\Helpers\FinalInstallManager $finalInstall
     * @param \Kode\PixelPayload\\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent(); 
        event(new LaravelInstallerFinished);
        return view('pdo::finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
