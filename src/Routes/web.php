<?php



Route::group(['prefix' => 'install', 'as' => 'LaravelInstaller::', 'namespace' => 'Kode\PixelPayload\Controllers', 'middleware' => ['web','install']], function () {
Route::get('done', [
    'as' => 'done',
    'uses' => 'WelcomeController@installedDone',
]);
});

Route::group(['prefix' => 'install', 'as' => 'LaravelInstaller::', 'namespace' => 'Kode\PixelPayload\Controllers', 'middleware' => ['web','install']], function () {
    Route::get('/', [
        'as' => 'welcome',
        'uses' => 'WelcomeController@welcome',
    ]);


    Route::get('environment', [
        'as' => 'environment',
        'uses' => 'EnvironmentController@environmentMenu',
    ]);

    Route::get('requirements', [
        'as' => 'requirements',
        'uses' => 'RequirementsController@requirements',
    ]);

    Route::get('permissions', [
        'as' => 'permissions',
        'uses' => 'PermissionsController@permissions',
    ]);

    Route::get('environment/wizard', [
        'as' => 'environmentWizard',
        'uses' => 'EnvironmentController@environmentWizard',
    ]);

    Route::post('environment/saveWizard', [
        'as' => 'environmentSaveWizard',
        'uses' => 'EnvironmentController@saveWizard',
    ]);

    Route::get('environment/classic', [
        'as' => 'environmentClassic',
        'uses' => 'EnvironmentController@environmentClassic',
    ]);

    Route::post('environment/saveClassic', [
        'as' => 'environmentSaveClassic',
        'uses' => 'EnvironmentController@saveClassic',
    ]); 

    Route::get('database', [
        'as' => 'database',
        'uses' => 'DatabaseController@database',
    ]);

    Route::get('final', [
        'as' => 'final',
        'uses' => 'FinalController@finish',
    ])->withoutMiddleware(['install']);;
});
