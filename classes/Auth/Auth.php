<?php
namespace DaVinci\Auth;

use DaVinci\Models\Usuario;
use DaVinci\Security\Hash;

class Auth
{
	private ?Usuario $usuario = null;

	public function login(string $email, string $password): bool
	{
		$usuario = (new Usuario)->traerPorEmail($email);

		if(!$usuario) return false;

		if(!Hash::verificar($password, $usuario->getPassword())) return false;

		$this->autenticarUsuario($usuario);

		return true;
	}

	public function logout()
	{
		$this->usuario = null;
		unset($_SESSION['usuario_id']);
	}

	public function autenticarUsuario(Usuario $usuario)
	{
		$_SESSION['usuario_id'] = $usuario->getUsuarioId();
	}

	public function autenticado(): bool
	{
		return isset($_SESSION['usuario_id']);
	}

	public function getId(): ?int
	{
		return $_SESSION['usuario_id'] ?? null;
	}

	public function getUsuario(): ?Usuario
	{
		if(!$this->autenticado()) return null;

		if(!$this->usuario) {
			$this->usuario = (new Usuario)->traerPorId($_SESSION['usuario_id']);
		}

		return $this->usuario;
	}

    public function esAdministrador(): bool {
        $rol_fk = $this->getUsuario()->getRolFk();

        return $rol_fk == 1;
    }
}