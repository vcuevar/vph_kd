<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use \COM;
use Session;
use Illuminate\Support\Facades\Auth;
class SAP extends Model
{
     private static $vCmp = false;


    public static function Connect(){
        self::$vCmp = new COM ('SAPbobsCOM.company') or die ("Sin conexión");
        self::$vCmp->DbServerType="6"; 
        self::$vCmp->server = "SERVER-SAPBO";
        self::$vCmp->LicenseServer = "SERVER-SAPBO:30000";
        self::$vCmp->CompanyDB = "SALOTTO";
        self::$vCmp->username = "controlp";
        self::$vCmp->password = "190109";
        self::$vCmp->DbUserName = "sa";
        self::$vCmp->DbPassword = "B1Admin";
        self::$vCmp->UseTrusted = false;
        self::$vCmp->language = "6";
        $lRetCode = self::$vCmp->Connect;
        if ($lRetCode <> 0) {
           return self::$vCmp->GetLastErrorDescription();
        } else {
            return 'Conectado';
        }  
   }
   public static function ProductionOrderStatus($orden, $status){
        //Cambia el status de una orden en SAP. el status sigue los siguientes criterios
        //--P -> 0 OP95 planificado
        //--R -> 1 OP271 liberado
        //--L -> 2 OP4 cerrado
        //--C -> 3 OP1 cancelado
    (self::$vCmp == false) ? self::Connect(): '';
    $vItem = self::$vCmp->GetBusinessObject("202");
    $RetVal = $vItem->GetByKey($orden);
    $vItem->ProductionOrderStatus = $status;
    $vItem->Update;
    if ($vItem->ProductionOrderStatus <> $status) {
        return false;
    } else {
        return true;
    }
   }
   public static function SaveArticulo($array){
    //pKey
       //proveedor Mainsupplier
       //metodo U_Metodo
       //grupop U_GrupoPlanea
       //comprador U_Comprador
       //costocompras PriceList->Price
       //monedacompras PriceList->Currency
      
    (self::$vCmp == false) ? self::Connect(): '';
    //self::$vCmp->XmlExportType("xet_ExportImportMode");
    $vItem = self::$vCmp->GetBusinessObject("4");
    $RetVal = $vItem->GetByKey($array['pKey']);
    //Actualizar Proveedor
    $vItem->Mainsupplier = $array['proveedor'];
    //Seleccionar lista de Precios
    $vItem->PriceList->SetCurrentLine(8);
    $vItem->PriceList->Price = $array['costocompras'];
    $vItem->PriceList->Currency = $array['monedacompras'];

   //$arrayName = array(
    //'metod' => $vItem->UserFields->Fields->Item('U_Metodo')->Value, 
    //'grupo' => $vItem->UserFields->Fields->Item('U_GrupoPlanea')->Value, 
    //'comp' => $vItem->UserFields->Fields->Item('U_Comprador')->Value, 
    //);  
    //dd($arrayName);
    $vItem->UserFields->Fields->Item('U_Metodo')->Value = $array['metodo'];
    $vItem->UserFields->Fields->Item('U_GrupoPlanea')->Value = $array['grupop'];
    $vItem->UserFields->Fields->Item('U_Comprador')->Value = $array['comprador'];

    $retCode = $vItem->Update; 
    if ($retCode != 0) {
        return self::$vCmp->GetLastErrorDescription();
    } else {
        return 'ok';
    }  
                   
   }
    public static function Connect2(){
        self::$vCmp = new COM ('SAPbobsCOM.company') or die ("Sin conexión");
        self::$vCmp->DbServerType="6"; 
        self::$vCmp->server = "SERVER-SAPBO";
        self::$vCmp->LicenseServer = "SERVER-SAPBO:30000";
        self::$vCmp->CompanyDB = "SALOTTO";
        self::$vCmp->username = "sistema3";
        self::$vCmp->password = "beto";
        self::$vCmp->DbUserName = "sa";
        self::$vCmp->DbPassword = "B1Admin";
        self::$vCmp->UseTrusted = false;
        self::$vCmp->language = "6";
        $lRetCode = self::$vCmp->Connect;
        if ($lRetCode <> 0) {
           return self::$vCmp->GetLastErrorDescription();
        } else {
            return 'Conectado';
        }  
   }
   public static function Transfer($data){   

        $id = $data['id_solicitud']; 
        (self::$vCmp == false) ? self::Connect2(): '';
        //self::$vCmp->XmlExportType("xet_ExportImportMode");
        $vItem = self::$vCmp->GetBusinessObject("67");
        //Obtener Lineas de una Transferencia
       // $RetVal = $vItem->GetByKey("7782");
        //dd($vItem->Printed);
        //echo $vItem->Lines->SetCurrentLine(0);
        //echo $vItem->Lines->ItemCode;
        //dd($data['items']);
        DB::beginTransaction();
        if (count($data['items']) > 0) {
            //Crear Transferencia
            $vItem->DocDate = (new \DateTime('now'))->format('Y-m-d H:i:s');
            $vItem->FromWarehouse = $data['almacen_origen']; //origen
            $vItem->PriceList = $data['pricelist'];
            $vItem->FolioNumber = $id;//**/Vale:solicitud
            $vItem->Comments = $data['observaciones'];
            $vItem->JournalMemo = "Traslados -";
            
            foreach ($data['items'] as $item) {
                $varDestino = explode(' - ',$item->Destino);
                
                $surtido = DB::select('select WTR1.ItemCode, SUM(Quantity) as Cant
                    from WTR1 
                    inner join SIZ_TransferSolicitudesMP as t on t.DocEntry_Transfer = WTR1.DocEntry
                    left join SIZ_MaterialesSolicitudes as s on s.Id_Solicitud = t.Id_Solicitud and s.ItemCode = WTR1.ItemCode
                    where t.Id_Solicitud = ? AND s.ItemCode = ?
                    group by WTR1.ItemCode', [$id, $item->ItemCode]);
                
                $CantSurtida = array_sum(array_pluck($surtido, 'Cant'));
                if ($CantSurtida >= $item->Cant_Autorizada) {
                    DB::rollBack();
                    return 'Error, Material ' . $item->ItemCode . ' ya fue surtido.';
                }
               //agregar lineaS               
               if ($data['almacen_origen'] == 'APG-PA') {
                    if ($item->Cant_PendienteA >= $item->CA) {
                        $vItem->Lines->Quantity = $item->CA;
                        DB::table('SIZ_MaterialesSolicitudes')
                        ->where('Id', $item->Id)
                        ->update(['Cant_PendienteA' => ($item->Cant_PendienteA - $item->CA),                      
                        'Cant_ASurtir_Origen_A' => 0]);
                        $vItem->Lines->ItemCode = $item->ItemCode;
                        $vItem->Lines->WarehouseCode = trim($varDestino[0]);
                        if ($item->BatchNum > 0) {
                            $lotes = DB::table('SIZ_MaterialesLotes')
                                ->where('Id_Item', $item->Id)
                                ->where('alm', 'APG-PA')
                                ->get();
                            if (count($lotes) > 0) {
                                foreach ($lotes as $l) {
                                    $vItem->Lines->BatchNumbers->BatchNumber = $l->lote;
                                    $vItem->Lines->BatchNumbers->Quantity = $l->Cant;
                                    $vItem->Lines->BatchNumbers->Add();
                                }
                            }else{
                                DB::rollBack();
                                return 'Error, Material '.$item->ItemCode. ' sin lotes asignados';
                            }
                        }               
                        $vItem->Lines->Add(); 
                        if (($item->Cant_PendienteA - $item->CA) == 0) {
                            DB::table('SIZ_MaterialesSolicitudes')
                            ->where('Id', $item->Id)
                            ->update(['EstatusLinea' => 'T']);
                        } elseif (($item->Cant_PendienteA - $item->CA) > 0) {
                            // DB::table('SIZ_MaterialesSolicitudes')
                            //     ->where('Id', $item->Id)
                            //     ->update(['EstatusLinea' => 'P']);
                        }
                    }
                } elseif ($data['almacen_origen'] == 'AMP-ST') {
                   
                    if ($item->Cant_PendienteA >= $item->CB) {
                        $vItem->Lines->Quantity = $item->CB; 
                        DB::table('SIZ_MaterialesSolicitudes')
                        ->where('Id', $item->Id)
                        ->update(['Cant_PendienteA' => ($item->Cant_PendienteA - $item->CB),                       
                        'Cant_ASurtir_Origen_B' => 0]);
                        $vItem->Lines->ItemCode = $item->ItemCode;
                        $vItem->Lines->WarehouseCode = trim($varDestino[0]);
                        if ($item->BatchNum > 0) {
                            $lotes = DB::table('SIZ_MaterialesLotes')
                                ->where('Id_Item', $item->Id)
                                ->where('alm', 'AMP-ST')
                                ->get();
                            if (count($lotes) > 0) {
                                foreach ($lotes as $l) {
                                    $vItem->Lines->BatchNumbers->BatchNumber = $l->lote;
                                    $vItem->Lines->BatchNumbers->Quantity = $l->Cant;
                                    $vItem->Lines->BatchNumbers->Add();
                                }
                            } else {
                                DB::rollBack();
                                return 'Error, Material ' . $item->ItemCode . ' sin lotes asignados';
                            }
                        }               
                        $vItem->Lines->Add(); 
                        if (($item->Cant_PendienteA - $item->CB) == 0) {
                            DB::table('SIZ_MaterialesSolicitudes')
                            ->where('Id', $item->Id)
                            ->update(['EstatusLinea' => 'T']);                                               
                        }elseif (($item->Cant_PendienteA - $item->CB) > 0) {
                            // DB::table('SIZ_MaterialesSolicitudes')
                            // ->where('Id', $item->Id)
                            // ->update(['EstatusLinea' => 'P']);
                        }
                    }
                }
                
            }
        }else{
            DB::rollBack();
            return 'Error, No hay ningun material que surtir';
        }       
        //Guardar Transferencia
       
        if ($vItem->Add() == 0) {// cero es correcto
            DB::commit();
            $docentry = DB::table('OWTR')
            ->where('FolioNum', $id)
            ->max('DocEntry');
            DB::table('SIZ_TransferSolicitudesMP')->insert(
                ['Id_Solicitud' => $id, 'DocEntry_Transfer' => $docentry, 'Usuario' => Auth::user()->U_EmpGiro]
            );          
            return $docentry;
        } else {
            DB::rollBack();
            return 'Error desde SAP: '.self::$vCmp->GetLastErrorDescription();
           
        }  
   }
   public static function Transfer2($data){   
  
        $id = $data['id_solicitud']; 
        (self::$vCmp == false) ? self::Connect2(): '';
        //self::$vCmp->XmlExportType("xet_ExportImportMode");
        $vItem = self::$vCmp->GetBusinessObject("67");
        
        DB::beginTransaction();
        if (count($data['items']) > 0) {
            //Crear Transferencia
            $vItem->DocDate = (new \DateTime('now'))->format('Y-m-d H:i:s');
            $vItem->FromWarehouse = $data['almacen_origen']; //origen
            $vItem->PriceList = $data['pricelist'];
            $vItem->FolioNumber = $id;//**/Vale:solicitud
            $vItem->Comments = $data['observaciones'];
            $vItem->JournalMemo = "Traslados -";
            
            foreach ($data['items'] as $item) {
                $varDestino = explode(' - ',$item->Destino);
               //agregar lineaS 
                    if ($item->Cant_PendienteA >= $item->CA) {
                        $vItem->Lines->Quantity = $item->CA;
                        DB::table('SIZ_MaterialesTraslados')
                        ->where('Id', $item->Id)
                        ->update(['Cant_PendienteA' => ($item->Cant_PendienteA - $item->CA),                      
                        'Cant_ASurtir_Origen_A' => ($item->Cant_PendienteA - $item->CA)]);
                        $vItem->Lines->ItemCode = $item->ItemCode;
                        $vItem->Lines->WarehouseCode = trim($varDestino[0]);              
                        if ($item->BatchNum > 0) {
                            $lotes = DB::table('SIZ_MaterialesLotes')
                                ->where('Id_Item', $item->Id)
                                ->where('alm', $data['almacen_origen'])
                                ->get();
                            if (count($lotes) > 0) {
                                foreach ($lotes as $l) {
                                    $vItem->Lines->BatchNumbers->BatchNumber = $l->lote;
                                    $vItem->Lines->BatchNumbers->Quantity = $l->Cant;
                                    $vItem->Lines->BatchNumbers->Add();
                                }
                            } else {
                                DB::rollBack();
                                return 'Error, Material ' . $item->ItemCode . ' sin lotes asignados';
                            }
                        }              
                        $vItem->Lines->Add(); 
                        if (($item->Cant_PendienteA - $item->CA) == 0) {
                            DB::table('SIZ_MaterialesTraslados')
                            ->where('Id', $item->Id)
                            ->update(['EstatusLinea' => 'T']);                   
                            
                        }
                    }
                 
                
            }
        }else{
            return 'No hay ningun material que surtir';
        }       
        //Guardar Transferencia
       
        if ($vItem->Add() == 0) {// cero es correcto
            $docentry = DB::table('OWTR')
            ->where('FolioNum', $id)
            ->max('DocEntry');
            DB::commit();
            DB::table('SIZ_TransferSolicitudesMP')->insert(
                ['Id_Solicitud' => $id, 'DocEntry_Transfer' => $docentry, 'Usuario' => Auth::user()->U_EmpGiro]
            );          
            return $docentry;
        } else {
            DB::rollBack();
            return 'Error desde SAP: '.self::$vCmp->GetLastErrorDescription();
           
        }  
   }

    public static function ReciboProduccion($docEntry, $Cant){
        (self::$vCmp == false) ? self::Connect(): '';
   
        $vItem = self::$vCmp->GetBusinessObject("59");
        $vItem->Lines->BaseEntry = $docEntry; 
        $vItem->Lines->BaseType = '202'; 
        $vItem->Lines->TransactionType = '0'; // botrntComplete
        $vItem->Lines->Quantity = $Cant;
        $vItem->Lines->WarehouseCode = 'APT-ST';
        $vItem->Lines->Add(); 
        if ($vItem->Add() == 0) {// cero es correcto   
                return 'Recibo de producción creado correctamente';
        } else {
                $descripcionError = self::$vCmp->GetLastErrorDescription();    
                if (strpos($descripcionError, 'IGN1.WhsCode][line: 1') !== false) {
                $descripcionError = $descripcionError.' Uno o más materiales tienen stock negativo.';
                }
                return 'Error SAP: '.$descripcionError;
        }  
   }
}

/*
    Thanks a lot for this post.

    For me the following is running

    $oItem=$vCmp->GetBusinessObject(4);

    $RetBool=$oItem->GetByKey("A1010");

    echo "<br>". $RetBoll;

    echo "<br>". $oItem->ItemName;

    This return:

    1

    Nom de l'article

    For recordset

    $oRS=$vCmp->GetBusinessObject(300);

    $oRS->DoQuery("Select Top 10 itemcode,itemName from oitm")

    $oRS->MoveFirst

    while ($oRS->EOF!=1){

    echo "<BR>".$oRS->Fields->Item(0)->value." ".$oRS->Fields->Item(1)->value;

    $oRS->MoveNext;

    }

    To add an order

    $oOrder=$vCmp->GetBusinessObject(17);

    $oOrder->CardCode="C01";

    $oOrder->DocDueDate="06/04/2009";

    $oOrder->Lines->Itemcode="A1010";

    $oOrder->Quantity=100;

    $RetCode=$oOrder->Add;

    $Nk="";

    if ($RetCode==0) {

    $vCmp->GetNewObjectCode($Nk);

    echo "<BR>" ."Doc Entry ".$vCmp->GetNewObjectCode($Nk);

    }
*/