<?php 
namespace DaVinci\Auth;

use DaVinci\Security\Hash;

class RestablecerPassword
{
	public function enviarEmail(string $email)
	{
		$token = Hash::generarToken();
		
		// TODO: Buscar el usuario del email, grabar en la base, y enviar el correo.
	}
}