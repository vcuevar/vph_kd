<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Session;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

//entre comillas la ruta a la que deseas redireccionar
    protected $redirectTo = 'home';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    public function postLogin(Request $request)
    {
        if ($request->get('password') != "1234"){
            try {
                if (Auth::attempt(['U_EmpGiro' => $request->get('id'), 'password'   => $request->get('password'), 'status' => 1])) {
                    //dd($request->all());
                    if(User::isProductionUser()){
                     
                       Session::flash('send', 'send');
                       Session::flash('miusuario', '');
                       Session::flash('pass', '0123');
                       Session::flash('pass2', '1234');
                       return redirect()->action('Mod01_ProduccionController@traslados');   
                    }else{
                       return redirect()->intended('home');
                    }
                }else{
                    return redirect($this->loginPath())
                        ->withInput($request->only($this->loginUsername(), 'remember'))
                        ->withErrors('Usuario/contraseña inválidos, ó Baja');
                }
            } catch(\Exception $e) {
                echo ''. $e->getMessage();
            }
    
        }else{
            if (Auth::attempt(['U_EmpGiro' => $request->get('id'), 'password'   => $request->get('password'), 'status' => 1])) {
                //dd($request->all());                     
                //return view('auth.updatepassword');
                return redirect()->route('viewpassword');

            }else{
                return redirect($this->loginPath())
                    ->withInput($request->only($this->loginUsername(), 'remember'))
                    ->withErrors('Usuario/contraseña inválidos, ó Baja');
            }
           
        }

    }
   
}
