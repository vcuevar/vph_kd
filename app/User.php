<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
use Auth;
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dbo.OHEM';
    protected $primaryKey = 'U_EmpGiro';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'U_CP_Password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'U_CP_Password', 'U_remember_token',
    ];


    public function getAuthPassword()
    {
        return $this->U_CP_Password;
    }

    public function getRememberToken()
    {
        return $this->U_remember_token;
    }

    public function setRememberToken($value)
    {
        $this->U_remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'U_remember_token';
    }

    Public function  scopeName($query, $name){

        if (trim($name) != ""){
            $query
                 ->join('OUDP', 'OHEM.dept', '=', 'OUDP.Code')
                ->innerJoin('HEM6', 'OHEM.empID', '=', 'HEM6.empID')
                ->leftJoin('OHST', 'OHEM.status', '=', 'OHST.statusID')
                ->where(\DB::raw("(firstName + ' ' +lastName)"), "LIKE" , "%$name%");
        }

    }

    public function getTareas(){
        $actividades = DB::table('OHEM')
            ->leftJoin('HEM6', 'OHEM.empID', '=', 'HEM6.empID')
            ->join('OHTY', 'OHTY.typeID', '=', 'HEM6.roleID')
            ->leftJoin('Siz_Modulos_Grupo','Siz_Modulos_Grupo.id_grupo' ,'=', 'HEM6.roleID')
            ->leftJoin('Siz_Modulo','Siz_Modulo.id' ,'=', 'Siz_Modulos_Grupo.id_modulo')
            ->leftJoin('Siz_Menu_Item','Siz_Menu_Item.id' ,'=', 'Siz_Modulos_Grupo.id_menu')
            ->leftJoin('Siz_Tarea_menu','Siz_Tarea_menu.id' ,'=', 'Siz_Modulos_Grupo.id_tarea')
            ->where('U_EmpGiro', Auth::user()->U_EmpGiro)
            ->where('HEM6.line', '1')
            ->whereNotNull('Siz_Modulos_Grupo.id_tarea')
            ->select('Siz_Modulos_Grupo.*',
                'Siz_Modulo.name AS modulo',
                'Siz_Menu_Item.name AS menu',
                'Siz_Tarea_menu.name AS tarea',
                'Siz_Tarea_menu.route AS ruta')
            ->orderBy('Siz_Modulo.name', 'asc')
            ->orderBy( 'Siz_Menu_Item.name', 'asc')
            ->orderBy( 'Siz_Tarea_menu.name', 'asc')
            ->get();
        return $actividades;
    }

    public static function isAdmin(){
        $admin=DB::table('HTM1')->where('empID',Auth::user()->empID)->first();
    
        if(isset($admin)){
            if($admin->teamID==1)
            {
                return true;
            }
            else
            {           
                return false;
            }            
        }
        else
        {           
            return false;
        }
       }

       public static function isProductionUser(){
        $admin=DB::table('OHEM')
        ->join('HEM6', 'OHEM.empID', '=', 'HEM6.empID')
        ->leftJoin('OHTY', 'OHTY.typeID', '=', 'HEM6.roleID')
        ->where('OHEM.empID',Auth::user()->empID)
        ->select('OHTY.typeID','OHTY.name')
        ->first();
   
        if(isset($admin)){
            if($admin->typeID==8)
            {
                return true;
            }
            else
            {           
                return false;
            }            
        }
        else
        {           
            return false;
        }
       }
       public static function getUserType($empId){
        $admin=DB::table('OHEM')
        ->where('OHEM.empID', $empId)
        ->select('Ohem.position')
        ->first();
   
        if(isset($admin)){
            return $admin->position;     
        }
        else
        {           
            return false;
        }
       }
       public static function getCountNotificacion(){
        $id_user=Auth::user()->U_EmpGiro;
        $noticias=DB::select(DB::raw("SELECT * FROM Siz_Noticias WHERE Destinatario='$id_user'and Leido='N'"));     
        return count($noticias);
       }
     public function getPuesto(){
         //DEPARTAMENTO
         $dept = DB::table('OUDP')->where('Code', $this->dept)
        ->value('Name');
        //POSICION
        $pos = DB::table('OHPS')->where('posID', $this->position)
        ->value('name');
        //PUESTO
        $puesto = $this->jobTitle;

        return $dept." - ".$pos." - ".$puesto;
     }
}
