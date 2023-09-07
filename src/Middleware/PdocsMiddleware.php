<?php

namespace Kode\PixelPayload\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Schema;
class PdocsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try { 
            $installedLogFile = storage_path(config('requirements.core.cacheFile'));
            if (! file_exists($installedLogFile)) {
                return redirect()->to(url('/').strDec('L2luc3RhbGw='));
            }
            DB::connection()->getPdo();
            if(!Schema::hasTable(config('requirements.core.dbTbl'))) {
                if(file_exists($installedLogFile)){
                    @unlink($installedLogFile); 
                }
                return redirect()->to(url('/').strDec('L2luc3RhbGw='));
            }
            return $next($request);
        } catch (\Exception $e) {
            return redirect()->to(url('/').strDec('L2luc3RhbGw='));
        }
    }
}
