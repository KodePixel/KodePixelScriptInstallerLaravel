<?php

namespace Kode\PixelPayload\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (! file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath()
    {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function saveFileWizard(Request $request)
    {
        #changed
        $status = true;
        try {
            $envFilePath = $this->envPath;
            $envFileData = file_get_contents($envFilePath);
            $envFileData = str_replace([
                'APP_NAME=',
                'APP_ENV=',
                'APP_KEY=base64:sEe+3RY8GysxAufbesdqY70UDaZEvNfyKH/xm0DLKmw=',
                'APP_DEBUG=',
                'APP_URL=',
                'DB_CONNECTION=',
                'DB_HOST=',
                'DB_PORT=',
                'DB_DATABASE=',
                'DB_USERNAME=',
                'DB_PASSWORD=',
            ], [
                'APP_NAME='.$request->input('app_name'),
                'APP_ENV='.$request->input('environment'),
                'APP_KEY='.'base64:'.base64_encode(Str::random(32)),
                'APP_DEBUG=false',
                'APP_URL='.$request->input('app_url'),
                'DB_CONNECTION='.$request->input('database_connection'),
                'DB_HOST='.$request->input('database_hostname'),
                'DB_PORT='.$request->input('database_port'),
                'DB_DATABASE="'.$request->input('database_name').'"',
                'DB_USERNAME="'.$request->input('database_username').'"',
                'DB_PASSWORD="'.$request->input('database_password').'"'
            ], $envFileData);
            file_put_contents($envFilePath, $envFileData);
        } catch (Exception $e) {
            $status = false;
        }
        return $status;
    } 
}
