<html>
<head>
<title>Registro Pago</title>
</head>
<body>

<?php echo validation_errors(); ?>

<?php echo form_open('Registropagos_bta'); ?>
 <fieldset>
<h3> <center>Formulario Pago: </center></h3>	 
<h4>Cantidad Depositada</h4>
<input type="text" name="cant" value="<?php echo set_value('cant'); ?>" size="30" />
<h4>Numero Referencia Cheque</h4>
<input type="text" name="numrefcheq" value="<?php echo set_value('numrefcheq'); ?>" size="50" />

<h4>Elija Inmueble a pagar:</h4>
<?php 
// aqui se muestran los radio buttons.. y si funcionan jejeje
echo $htmlradio. PHP_EOL;  ?>
<div><input type="submit" value="Enviar" /></div>
 </fieldset>
 <?php 
echo anchor('Login_usuario/salir', 'Cerrar Sesión', 'title="Cerrar Sesión"');
?>
</form>

</body>
</html>

