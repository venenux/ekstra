<?php
/* 
This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License v3 or any other.
* 
* Este código es un modelo donde  se devuelve un conjunto de valores  en un array
*  emulando el resultado de un query en una bd 
*/
class Modelodatos extends CI_Model 
{ 

public function __construct() 
{
parent::__construct();
// aqui  se carga la libreria Curl hecha para codeigniter 2.algo 
	$this->load->library('curl');
}




public function getFullusers_data()
{ 
	$user1=array();
	$user2=array();
    $user3=array();

   $user1 ['login'] = 'user1'; 
   $user1 ['clave']=  '321';
   $user1 ['codarrendatario']='ARR100521';
   
 
   $user2 ['login'] = 'user2';
   $user2 [ 'clave']=  '123';
   $user2 ['codarrendatario']='ARR300722' ;
   
 $user3 ['login'] = 'user3'; 
 $user3['clave']=  '213';
 $user3['codarrendatario']= 'ARR800823';
 

$fullusers=array('1'=> $user1 ,'2'=>$user2,'3'=>$user3);

return $fullusers;



}

public function getRegistrosbycode($username)
{
/*$arreglopatrimonios['cod_arrendatario'] = '700125522';
$arreglopatrimonios['username'] = $username;
$arreglopatrimonios['userclave'] = '321';*/
$arraypatrimonioxarrendatario= array();
/*/datos ficticios primer arrendatario */
$pati1 = array('debe'=>22300, 'desde'=>'20170530', 'des_patrimonio'=>'Local 7 Oficina 4 Ruices','codpatrimonio'=>'PAT20170101010101');
$pati2 = array('debe'=>55690, 'desde'=>'20170810', 'des_patrimonio'=>'Galpon Valencia','codpatrimonio'=>'PAT20170101010102');
$pati3 = array('debe'=>232342, 'desde'=>'20170630', 'des_patrimonio'=>'Local  2 oficina  10 Lido','codpatrimonio'=>'PAT20170101010103');
// guardar los patrimonios
$patiuser1 = array('PAT20170101010101'=>$pati1, 'PAT20170101010102'=>$pati2, 'PAT20170101010103'=>$pati3);
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR100521']=$patiuser1 ;
/*/datos ficticios segundo arrendatario */
$pati21 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento  77 Sabal','codpatrimonio'=>'PAT2107882111');
// guardar los patrimonios
$patiuser2=array('PAT2107882111' =>$pati21 );
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR300722']=$patiuser2 ;
/*/datos ficticios tercer  arrendatario */
$pati1 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento 25 Sabana Grande','codpatrimonio'=>'PAT21078821778');
$pati3 = array('debe'=>21342, 'desde'=>'20170215', 'des_patrimonio'=>'Local  3 oficina  20 Lido','codpatrimonio'=>'PAT201701454503');
// guardar los patrimonios
$patiuser3=array('PAT21078821778'=>$pati1,'PAT201701454503'=>$pati3);
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR800823']=$patiuser3;
return $arraypatrimonioxarrendatario[$username];
}
public function getRegistros()
{
/*$arreglopatrimonios['cod_arrendatario'] = '700125522';
$arreglopatrimonios['username'] = $username;
$arreglopatrimonios['userclave'] = '321';*/
$arraypatrimonioxarrendatario= array();
/*/datos ficticios primer arrendatario */
$pati1 = array('debe'=>22300, 'desde'=>'20170530', 'des_patrimonio'=>'Local 7 Oficina 4 Ruices','codpatrimonio'=>'PAT20170101010101');
$pati2 = array('debe'=>55690, 'desde'=>'20170810', 'des_patrimonio'=>'Galpon Valencia','codpatrimonio'=>'PAT20170101010102');
$pati3 = array('debe'=>232342, 'desde'=>'20170630', 'des_patrimonio'=>'Local  2 oficina  10 Lido','codpatrimonio'=>'PAT20170101010103');
// guardar los patrimonios
$patiuser1 = array('PAT20170101010101'=>$pati1, 'PAT20170101010102'=>$pati2, 'PAT20170101010103'=>$pati3);
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR100521']=$patiuser1 ;
/*/datos ficticios segundo arrendatario */
$pati21 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento  77 Sabal','codpatrimonio'=>'PAT2107882111');
// guardar los patrimonios
$patiuser2=array('PAT2107882111' =>$pati21 );
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR300722']=$patiuser2 ;
/*/datos ficticios tercer  arrendatario */
$pati1 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento 25 Sabana Grande','codpatrimonio'=>'PAT21078821778');
$pati3 = array('debe'=>21342, 'desde'=>'20170215', 'des_patrimonio'=>'Local  3 oficina  20 Lido','codpatrimonio'=>'PAT201701454503');
// guardar los patrimonios
$patiuser3=array('PAT21078821778'=>$pati1,'PAT201701454503'=>$pati3);
//  asociar ese arrendario con sus inmuebles en un mega array
$arraypatrimonioxarrendatario['ARR800823']=$patiuser3;
return $arraypatrimonioxarrendatario;
}

public function registrarpago( $elregistro)
{ // simula el guardado de los dato en una bd
 //será usado un archivo de (mete)texto, usando append para que escriba los registros sucesivos y ver si el proceso de post
 // hasta la escritura sean correctos
 
 /*     $elregistro=array(
		                                       'intranet'=>$data['username'], // el intranet será el indice de este arreglo
		                                        'cant_pago'=>$this->input->post('cant'),
		                                       'numref'=>$this->input->post('numrefcheq'),
		                                       'cod_pat'=>$this->input->post('patrimonio')
 	*/
 //esta ruta está "entubada":debe ser ruta absoluta
 // por ahora no sé como hacer para que sea portable este código
  $fp = fopen("/home/systemas/Devel/CodeEmudb/bd.txt", "a"); // se abra para agregar registro en el orden como llegan
fputs($fp, "SE REGISTRA PAGO:". PHP_EOL);
fputs($fp, "Nombre/Intranet Usuario:  ".$elregistro['intranet'] . PHP_EOL);
fputs($fp, "Número referencia Cheque:  ".$elregistro['numref'] . PHP_EOL);
fputs($fp, "Cantidad Depositada:  ".$elregistro['cant_pago']. PHP_EOL);
fputs($fp, "Código patrimonio Pagado:  ".$elregistro['cod_pat']. PHP_EOL);
fputs($fp, " *                                                                                                        *". PHP_EOL);
fclose($fp);

return 'Pago registrado';
 
}// fin registrarpagos

public function traer_datos_json_nodatabase(){
// solicitar al emulador db el json con los posibles usuarios del sistema:  
$input_data = json_decode(trim(file_get_contents('php://input')), true);
}// fin traer datos
}// fin del modelol
