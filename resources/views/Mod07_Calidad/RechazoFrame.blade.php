
<html  ng-app='app' >
<head>
<title>Title of the document</title>

<base target="_parent" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<style>
* { box-sizing: border-box; }
body {
  font: 16px Arial; 
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
input {
  border: 1px solid transparent;
  background-color: #fff;
  padding: 10px;
  font-size: 16px;
}
input[type=text] {
  background-color: #fff;
  width: 100%;
}
input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
} 
iframe:focus { 
    outline: none;
}

iframe[seamless] { 
    display: block;
}   
</style>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.2/angular.min.js"></script>
</head>
<body ng-controller="MainController">

@include('partials.alertas')

<form autocomplete="off"  method="post" action="{!!action('Mod07_CalidadController@RechazoIn')!!}">
{{ csrf_field() }}
<div class="row">
<div class="autocomplete form-group col-md-6 col-sm-6">
<label>Fecha de Recepción</label>
<input class="form-control"data-date-format="DD MMMM YYYY" id="Fech_Recp"type="date" name="Fech_Recp"required>
    
  </div>
  <div class="autocomplete form-group col-md-6 col-sm-6">
  <label>Fecha de Revisión</label>
  <input class="form-control" data-date-format="DD MMMM YYYY" id="Fech_Rev" type="date" name="Fech_Rev" required>  
  
  </div>
  </div>

  <div class="row">
  <div class="form-group col-md-6 col-sm-6">
<label>Código del Proveedor</label>
    <div class="autocomplete input-group">
         <input class="form-control" id="Id_prov" type="text" ng-model="codigoproveedor" name="Id_prov" placeholder="Ejemplo:P112..."required>
      <span class="input-group-btn">
      <button class="btn btn-default" type="button" ng-click="findCardName(codigoproveedor)"><i class="fas fa-bolt"></i>&nbsp;
      </button>
      </span>
      </div>
    </div>
 <div class="form-group col-md-6 col-sm-6">
  <label>Nombre del Proveedor</label>
    <div class="autocomplete input-group">
      <input class="form-control" id="Proveedor" type="text" ng-model="nombreproveedor" name="Proveedor" placeholder="Ejemplo:Distr..."required>
      <span class="input-group-btn">
      <button class="btn btn-default" type="button" ng-click="findCardCode(nombreproveedor)"><i class="fas fa-bolt"></i>&nbsp;</button>
      </span>
    </div>
    </div>
  </div>
   
<div class="row">
   <div class="col-md-3 col-sm-3 form-group">
<label>Código de Material</label>
    <div class="autocomplete input-group">
      <input type="text" class="form-control" id="Codigo" ng-model="codigoitem" name="Codigo"  placeholder="Ejemplo:100..."  required >
      <span class="input-group-btn">
      <button class="btn btn-default" type="button" ng-click="findItemName(codigoitem)"><i class="fas fa-bolt"></i>&nbsp;</button>
      </span>
    </div>
    </div>

   <div class="col-md-3 col-sm-3 form-group">
<label>Unidad de Medida</label>
    <div class="autocomplete input-group">
    <input type="text" class="form-control" id="Um" ng-model="unidadmedida" name="Um" required >
    </div>
    </div>

   <div class="form-group col-md-6 col-sm-6">
<label>Descripción de Material</label>
    <div class="autocomplete input-group">
 <input type="text" class="form-control" id="Material" ng-model="nombreitem" name="Material"placeholder="Ejemplo:Adap..." required >
      <span class="input-group-btn">
      <button class="btn btn-default" type="button" ng-click="findItemCode(nombreitem)"><i class="fas fa-bolt"></i>&nbsp;</button>
      </span>
    </div>
    </div>
  </div>
  <div class="autocomplete form-group col-sm-3 col-md-3">
    <label for="exampleFormControlTextarea1">Cantidad Aceptada</label>
    <input type="number" min="0"ng-model="n1" class="form-control" id="C_Aceptada" name="C_Aceptada"  step=".01"required >
  </div>

  <div class="autocomplete form-group col-sm-3 col-md-3">
    <label for="exampleFormControlTextarea1">Cantidad Rechazada</label>
    <input type="number" min="0"ng-model="n2" class="form-control" id="C_Rechazada" name="C_Rechazada"  step=".01"required >
  </div>

  <div class="autocomplete form-group col-sm-3 col-md-3">
    <label for="exampleFormControlTextarea1">Cantidad Revisada</label>
    <input type="number"  class="form-control" ng-model="n3 "id="C_Revisada" name="C_Revisada" value="@{{n1+n2 }}" readonly required > 
  </div>
  <div class="autocomplete form-group col-sm-3 col-md-3">
    <label for="exampleFormControlTextarea1">Cantidad  Total Recibida</label>
    <input type="number"  class="form-control" ng-model="n4"id="C_Recibida" name="C_Recibida" step=".01" min="1" required > 
  </div>
  <div class="autocomplete form-group col-sm-2 col-md-2">
    <label for="exampleFormControlTextarea1">Porcentaje de Aceptados</label>
    <input type="number"  class="form-control" id="" name="" value="@{{(n1/n4)*100 }}" readonly step=".01" required > 
  </div>

  <div class="autocomplete form-group col-sm-10 col-md-10">
    <label for="exampleFormControlTextarea1">Descripcion del Rechazo</label>
    <input type="text" class="form-control" id="D_Rechazo" name="D_Rechazo"  required >
  </div>

  <div class="autocomplete form-group col-sm-10 col-md-10">
    <label for="exampleFormControlTextarea1">Número de Factura</label>
    <input type="text" class="form-control" id="N_Doc" name="N_Doc" required >
  </div>

  <div class="autocomplete form-group col-sm-10 col-md-10">
    <label for="exampleFormControlTextarea1">Nombre del Inspector</label>
    <input type="text" class="form-control" id="Inspector" name="Inspector"
    value='{{Auth::user()->firstName.' '.Auth::user()->lastName}}'  readonly>
  </div>

  <div class="autocomplete form-group col-sm-10 col-md-10">
    <label for="exampleFormControlTextarea1">Observaciones</label>
    <textarea class="form-control" id="Observaciones"name="Observaciones" rows="3"required></textarea>
  </div>

<div>
  <button type="submit" class="btn btn-primary">Guardar</button> 
  </div>
</form>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.0/moment.min.js"></script>
<script>
$(document).ready(function(){
    $("#Fech_Recp").change(function(){
       document.getElementById('Fech_Rev').value=document.getElementById('Fech_Recp').value;
       var fecha = moment(document.getElementById('Fech_Recp').value);
       var fecha_format=fecha.format('Y-MM-DD');
   
       document.getElementById('Fech_Rev').setAttribute("min",fecha_format);
    });
  });
</script>
<script> 
    var globalvalue='';
    var codeP=window.parent.Codeprov;
    var NameP=window.parent.Nameprov;
    var Code_Ma=window.parent.CodeMaterial;
    var Name_Ma=window.parent.NameMaterial;
    var resultMateriales = window.parent.Materiales;
    var resultProveedores = window.parent.Proveedores;
    
</script>
    <script>
    function autocomplete(inp, arr, model) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
    
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();

      inp.value = this.value;
      globalvalue = this.value;
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "' ng-model='" + model + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              globalvalue = this.getElementsByTagName("input")[0].value;
              //alert(globalvalue);
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
    </script>
<script>
autocomplete(document.getElementById("Proveedor"), NameP, 'nombreproveedor');
autocomplete(document.getElementById("Id_prov"), codeP,'codigoproveedor');
autocomplete(document.getElementById("Codigo"), Code_Ma,'codigoitem');
autocomplete(document.getElementById("Material"), Name_Ma,'nombreitem');

var app = angular.module('app', []);
app.controller('MainController',  ['$document','$filter', '$scope', '$window', function($document, $filter, $scope, $window) {
  $scope.resultProveedores = $window.resultProveedores;
  $scope.resultMateriales = $window.resultMateriales;
    $scope.findCardName = function (codigoproveedor) {
      if($window.globalvalue.trim().length > 0){
      codigoproveedor = $window.globalvalue;
      }
     // $window.globalvalue = '';          
      var o5 = $filter('filter')($scope.resultProveedores, {CardCode: codigoproveedor}, true)[0];
      $scope.nombreproveedor = o5.CardName;
    };

    $scope.findCardCode = function (nombreproveedor) {
      if($window.globalvalue.trim().length > 0){
      nombreproveedor = $window.globalvalue;
      }
     // $window.globalvalue = '';      
      var o5 = $filter('filter')($scope.resultProveedores, {CardName: nombreproveedor}, true)[0];
      $scope.codigoproveedor = o5.CardCode;
    };

    $scope.findItemCode = function (nombreitem) {
      if($window.globalvalue.trim().length > 0){
        nombreitem = $window.globalvalue;
      }      
      //$window.globalvalue = '';      
      var obj = $filter('filter')($scope.resultMateriales, {ItemName: nombreitem}, true)[0];
      $scope.codigoitem = obj.ItemCode;
      $scope.unidadmedida = obj.UM;
    };

    $scope.findItemName = function (codigoitem) {
      if($window.globalvalue.trim().length > 0){
        codigoitem = $window.globalvalue;
      }      
      //$window.globalvalue = '';      
      var obj = $filter('filter')($scope.resultMateriales, {ItemCode: codigoitem}, true)[0];
      $scope.nombreitem = obj.ItemName;
      $scope.unidadmedida = obj.UM;
    };

}]);
</script>
<script>
    window.onload=function() {
      document.getElementById("Fech_Recp").value =new Date().toJSON().slice(0,10);
    }
    </script>


</html>