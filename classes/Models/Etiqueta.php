<?php

namespace DaVinci\Models;

use PDO;
use DaVinci\DB\DB;

class Etiqueta
{
	private int $etiqueta_id;
	private string $nombre;

	public function cargarDatosDeArray(array $data)
	{
		$this->etiqueta_id  = $data['etiqueta_id'];
		$this->nombre 		= $data['nombre'];
	}

	/**
	 * 
	 * @return self[]
	 */
	public function todo(): array
	{
		$db = DB::getConexion();
		$query = "SELECT * FROM etiquetas";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

		return $stmt->fetchAll();
	}

	public function cargarEtiquetas(array $ids): array
	{
		$db = DB::getConexion();
		$holders = array_fill(0, count($ids), '?');

		$holders = implode(',', $holders);

		$query = "SELECT nte.noticia_fk, e.* 
                FROM noticias_tienen_etiquetas nte
                JOIN etiquetas e
                ON nte.etiqueta_fk = e.etiqueta_id
                WHERE nte.noticia_fk IN (" . $holders . ")";
    	$stmt = $db->prepare($query);
    	$stmt->execute($ids);
    	
    	$salida = [];

    	while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    		$etiqueta = new Etiqueta;
    		$etiqueta->cargarDatosDeArray($fila);

    		
    		$salida[$fila['noticia_fk']][] = $etiqueta;
    	}
    	
    	return $salida;
	}

	/********* SETTERS & GETTERS *********/
	public function getEtiquetaId(): int
	{
		return $this->etiqueta_id;
	}

	public function setEtiquetaId(int $etiqueta_id)
	{
		$this->etiqueta_id = $etiqueta_id;
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