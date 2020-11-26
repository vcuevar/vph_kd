<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Hash;
use Input;
use DB;
use Session;
use Auth;
class FunctionsController extends Controller
{
 
    public function cambioPasswordUsers(Request $request){


$validator = Validator::make($request->all(), [
    'userId' => 'required',
    'password' => 'required|confirmed',  //regex:/\D{2}\d{3}/
    'password_confirmation' => 'required',
]);
if ($validator->fails()) {
    return redirect()
                ->back()
                ->withErrors($validator);
}

 try {
     $password = Hash::make(Input::get('password'));
     DB::table('dbo.OHEM')
         ->where('U_EmpGiro',Input::get('userId') )
         ->update(['U_CP_Password' => $password]);
                          
 } catch(\Exception $e) {
     return redirect()->back()->withErrors(array('msg' => $e->getMessage()));
 }
 $name = Auth::user()->firstName;
 Auth::logout(); Session::flush();
 Session::flash('mensaje', $name.' tu contraseña ha cambiado.');
 return  redirect()->route('auth/login');
}
public function viewpassword(){
    return view('auth.updatepassword');
}

}
