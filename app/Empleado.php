<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'EMP_EmpleadoId';
    public $timestamps = false;
    protected $fillable = [
        'EMP_Nombre', 'EMP_Activo', 'EMP_Eliminado'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
