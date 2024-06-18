<?php

namespace DaVinci\Models;

use PDO;
use DaVinci\DB\DB;

class EstadoPublicacion
{
	private int $estado_publicacion_id;
	private string $nombre;

    public function cargarDatosDeArray(array $fila)
    {
        $this->estado_publicacion_id 	= $fila['estado_publicacion_id'];
        $this->nombre 					= $fila['nombre'];
    }

	/**
	 * Obtiene todos los estados de publicación.
	 * 
	 * @return self[]
	 */
	public function todo(): array
	{
		$db = DB::getConexion();
		$query = "SELECT * FROM estados_publicacion";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

		return $stmt->fetchAll();
	}

	/********* SETTERS & GETTERS *********/
	public function getEstadoPublicacionId(): int
	{
		return $this->estado_publicacion_id;
	}

	public function setEstadoPublicacionId(int $estado_publicacion_id)
	{
		$this->estado_publicacion_id = $estado_publicacion_id;
	}

	public function getNombre(): string
	{
		return $this->nombre;
	}

	public function setNombre(string $nombre)
	{
		$this->nombre = $nombre;
	}
}