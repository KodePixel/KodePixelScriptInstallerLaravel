<?php

namespace Kode\PixelPayload\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Kode\PixelPayload\Events\EnvironmentSaved;

use Kode\PixelPayload\Events\LaravelInstallerFinished;
use Kode\PixelPayload\Helpers\EnvironmentManager;
use Kode\PixelPayload\Helpers\FinalInstallManager;
use Kode\PixelPayload\Helpers\InstalledFileManager;
use Validator;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('pdo::environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('pdo::environment-wizard', compact('envConfig'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentClassic()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('pdo::environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     *   
     * 
     *
     *
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->EnvironmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        return $redirect->route('LaravelInstaller::environmentClassic')
                        ->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect ,InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
         
        $rules = config('requirements.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }   

      
        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }  


        try{
            event(new EnvironmentSaved($request)); 
            if($this->EnvironmentManager->saveFileWizard($request)){

                $path = (resource_path('database/') . strDec('ZGF0YWJhc2Uuc3Fs'));
                DB::unprepared(file_get_contents($path));
                event(new LaravelInstallerFinished);
                $finalInstall->runFinal();
                $fileManager->update();
                $environment->getEnvContent(); 
                return $redirect->route('LaravelInstaller::final');
            }


            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_name' =>  trans('installer_messages.environment.errors')
            ]); 

        }catch (\Exception $e) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_name' => trans('installer_messages.environment.wizard.form.db_error_importing'),
            ]); 
        }
    }

    /**
     * 
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return bool
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection"); 
        // if(!$this->envProcess($request)->status){
        //     return false;
        // }
        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host'   => $request->input('database_hostname'),
                        'port'   => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();

        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function envProcess(Request $request)
    {
        $dataArray[strDec('cHVyY2hhc2VkX2NvZGU=')] = $request->input(strDec('ZW52YXRvX2tleQ=='));
        $dataArray[strDec('YnV5ZXJfZW1haWw=')] = $request->input(strDec('YnV5ZXJfZW1haWw='));
        $dataArray[strDec('YnV5ZXJfZG9tYWlu')]   = url('/');
        try {
            $getData = json_decode(processURL($dataArray));
            return $getData;
        } catch (\Exception $e) {
            return false;
        }
    }
 
}
