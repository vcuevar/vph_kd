<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;
use Artisan;
class route_log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if (!Auth::check()) {
              
            return redirect()->route('auth/login');
        }
        $ruta = str_replace('%20', ' ', explode('/', $request->path())[1]);
        $ruta = str_replace('%C3%B7', '&#247;', $ruta);
        
        $result = DB::table('SIZ_routes_log')
            ->where('Usuario', Auth::user()->U_EmpGiro)
            ->where('route', $ruta)
            ->update(['ultimaFecha' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
        if($result > 1){// borrar si hay mas de 2 registros iguales
            DB::table('SIZ_routes_log') 
            ->where('Usuario', Auth::user()->U_EmpGiro)
            ->where('route', $ruta)->delete();
            $result = 0;
        }

        if ($result == 0) { //insertar si no hay algun registro
            DB::table('SIZ_routes_log')->insert(
                ['route' => $ruta, 'Usuario' => Auth::user()->U_EmpGiro, 'ultimaFecha' => (new \DateTime('now'))->format('Y-m-d H:i:s')]
            );
        }
        $result2 = DB::table('SIZ_routes_log')
            ->where('Usuario', Auth::user()->U_EmpGiro)            
            ->count();
        if ($result2 >= 7) {           
           for ($i=0; $i < ($result2 - 6); $i++) {              
                 $val = DB::table('SIZ_routes_log') 
                ->where('Usuario', Auth::user()->U_EmpGiro)
                ->orderBy('ultimaFecha', 'asc')
                ->first();               
                DB::delete('delete SIZ_routes_log where route = ?
                and Usuario = ? and ultimaFecha = ?', [$val->route, $val->Usuario, $val->ultimaFecha]);
            }
        }
        $respuesta = $next($request);
        if ($ruta == "TRASLADO &#247; AREAS") {
             Artisan::call('cache:clear');
            Artisan::call('config:cache');
        }
        return $respuesta;
    }
}
