<?php 

// Incluimos la clase de sesion
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

//generamos el id de session
if (!isset($_SESSION['initiated'])){
	
	session_regenerate_id();
	$_SESSION['initiated'] = true;
}


// Generamos el token
if (isset($_POST['message'])){
	if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']){
			$message = htmlentities($_POST['message']);
			$fp = fopen('./messages.txt', 'a');
			fwrite($fp, "$message<br />");
			fclose($fp);
	}
}

	$token = md5(uniqid(rand(), true));	
	$_SESSION['token'] = $token;

// Incluimos la clase Factory
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ActiveRecord/ActiveRecordFactory.php';

// Obtenemos la instancia del Registry
$oRegistry = \Sgu\Singleton\Sesion::getInstance()->getRegistry();

// Inicializamos la información personal
$oInformacionPersonal = null;

// Inicializamos la información de usuario
$oInformacionUsuario = null;

// Verificamos si hay información personal en la sesión
if ( $oRegistry->exists('informacion_personal') == false )
{
	// Si no hay información en la sesión, Obtenemos una instancia de persona
	$oInformacionPersonal = \Sgu\ActiveRecord\ActiveRecordFactory::getPersona();
}
else
{
	$oInformacionPersonal = $oRegistry->get('informacion_personal');
}

// Verificamos si hay información de usuario en la sesión
if ( $oRegistry->exists('informacion_usuario') == false )
{
	// Si no hay información en la sesión, inicializamos la información de usuario
	$oInformacionUsuario = \Sgu\ActiveRecord\ActiveRecordFactory::getUsuario();
}
else
{
	$oInformacionUsuario = $oRegistry->get('informacion_usuario');
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Formulario de Inscripc&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header_registro.php'; ?>
	
	<form action="Paso2.php" method="post">
		<fieldset>
			<legend>Informaci&oacute;n Personal:</legend>
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<ul>
				
				<li><label>Nombre de Usuario:</label></li>
				<li><input type="text" name="nombre_usuario" value="<?php echo $oInformacionUsuario->getNombre(); ?>"></li>
				
				<li><label>Contrase&ntilde;a:</label></li>
				<li><input type="password" name="contrasenia" value="<?php echo $oInformacionUsuario->getContrasenia(); ?>"></li>
				
				<li><label>Tipo de Usuario:</label></li>
				<li>
					<input type="hidden" name="tipo_usuario" value="2">
					<select name="select_tipo_usuario" disabled="disabled">
						<option value="1" <?php echo ( $oInformacionUsuario->getIdTipoUsuario() == '1' ) ? 'selected="selected"' : '' ; ?>>Administrador</option>
						<option value="2" <?php echo ( $oInformacionUsuario->getIdTipoUsuario() == null || $oInformacionUsuario->getIdTipoUsuario() == '2' ) ? 'selected="selected"' : '' ; ?>>Normal</option>
					</select>
				</li>
				
				<li><label>Apellido:</label></li>
				<li><input type="text" name="apellido" value="<?php echo $oInformacionPersonal->getApellido(); ?>"></li>
				
				<li><label>Nombre:</label></li>
				<li><input type="text" name="nombre" value="<?php echo $oInformacionPersonal->getNombre(); ?>"></li>
				
				<li><label>Tipo de Documento:</label></li>
				<li>
					<select name="tipo_documento">
						<option value="1" <?php echo ( $oInformacionPersonal->getIdTipoDocumento() == null || $oInformacionPersonal->getIdTipoDocumento() == '1' ) ? 'selected="selected"' : '' ; ?>>DNI</option>
						<option value="2" <?php echo ( $oInformacionPersonal->getIdTipoDocumento() == '2' ) ? 'selected="selected"' : '' ; ?>>LC</option>
						<option value="3" <?php echo ( $oInformacionPersonal->getIdTipoDocumento() == '3' ) ? 'selected="selected"' : '' ; ?>>LE</option>
					</select>
				</li>
				
				<li><label>N&uacute;mero de Documento:</label></li>
				<li><input type="text" name="numero_documento" value="<?php echo $oInformacionPersonal->getNumeroDocumento(); ?>"></li>
				
				<li><label>Sexo:</label></li>
				<li>
					<label class="radio"><input type="radio" name="sexo" value="M" <?php echo ( $oInformacionPersonal->getSexo() == null || $oInformacionPersonal->getSexo() == 'M' ) ? 'checked="checked"' : '' ; ?>> Masculino</label>
					<label class="radio"><input type="radio" name="sexo" value="F" <?php echo ( $oInformacionPersonal->getSexo() == 'F' ) ? 'checked="checked"' : '' ; ?>> Femenino</label>
				</li>
				
				<li><label>Nacionalidad:</label></li>
				<li><input type="text" name="nacionalidad" value="<?php echo $oInformacionPersonal->getNacionalidad(); ?>"></li>
			</ul>
			
			<div class="buttons">
				<input type="submit" name="bt_paso1" class="button" value="Siguiente">
			</div>
		</fieldset>
	</form>
	
	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>