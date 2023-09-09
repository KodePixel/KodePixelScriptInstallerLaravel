<?php

namespace Kode\PixelPayload\Middleware;

use Closure;
use Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 

class CanInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->alreadyInstalled()) {
            $installedRedirect = config('requirements.installedAlreadyAction');

            switch ($installedRedirect) {

                case 'route':
                
                    return redirect()->route('admin.login')->with('success','Already Installed');
                    break;

                case 'abort':
                    abort(config('requirements.installed.redirectOptions.abort.type'));
                    break;

                case 'dump':
                    try {
                        DB::connection()->getPdo();
                            if(!Schema::hasTable(strDec(config('requirements.core.dbTbl')))) {
                                $title = 'Database Connection';
                                $message = config('requirements.installed.redirectOptions.dump.database');
                                return view('pdo::error',compact('title','message')); 
                            }else{
                                $title = 'Software Setup';
                                $message = config('requirements.installed.redirectOptions.dump.data');
                                return view('pdo::error',compact('title','message'));
                            }
                        } catch (\Exception $e) {
                            $title = 'Database Connection Exception';
                            $message = $e->getMessage();
                            return view('pdo::error',compact('title','message')); 
                        }  
                    break;

                case '404':
                case 'default':
                default:
                    abort(404);
                    break;
            }
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return file_exists(storage_path(strDec(config('requirements.core.cacheFile'))));
    }

    public function checkDb()
    {
        try {
            DB::connection()->getPdo();
            if(!Schema::hasTable(strDec(config('requirements.core.dbTbl')))) {
                $title = 'Database Connection';
                $message = config('requirements.installed.redirectOptions.dump.database');
                return view('pdo::error',compact('title','message')); 
            }
        } catch (\Exception $e) {
            $title = 'Database Connection Exception';
            $message = $e->getMessage();
            return view('pdo::error',compact('title','message')); 
        }        
    }
}
