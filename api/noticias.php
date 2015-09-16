<?php

/**
 * Devuelve noticias obtenidas de la base de datos de mexicoxport.com en formato JSON.
 * Las noticias se devuelven por páginas y ordenadas de más reciente a más antigua.
 *
 * @param int   $categoria_id  ID de la categoría de la que se quieren ver noticias (default: todas)
 * @param int   $noticia_id    ID de la última noticia de la página anterior
 * @param int   $por_pagina    Cantidad de noticias a devolver en una página (default: 20)
 * @param int   $año           Si esta presente, indica el año del que se desean consultar noticias
 * @param int   $mes           Si esta presente, indica el mes del que se desean consultar noticias
 *
 * @author Kevin Perez <kevindperezm@gmail.com>
 * @copyright Mexicoxport 2015
 */

require_once __DIR__.'/utilidades.php';

function obtener_noticias_en_json() {
  $parametros = obtener_parametros_de_peticion();
  $consulta   = construir_consulta_para_noticias($parametros);
  $noticias   = utf8_encode_all(obtener_noticias_de_la_bd($consulta));

  $json = json_encode($noticias);
  header('Content-Type: application/json; charset=utf-8');
  header('Content-Length: ' . strlen($json));

  return $json;
}

function obtener_parametros_de_peticion() {
  $parametros_por_defecto = array('por_pagina' => 20);
  $parametros = array_map(function($parametro) { return sanitizar($parametro); }, $_GET);

  return array_merge($parametros_por_defecto, $parametros);
}

function construir_consulta_para_noticias($parametros) {
  extract($parametros);

  $consulta = 'SELECT * FROM noticias WHERE 1=1 ';
  if (isset($noticia_id))   $consulta .= " AND idNoticia  < $noticia_id";
  if (isset($categoria_id)) $consulta .= " AND idTematica = $categoria_id";

  $consulta .= generar_restriccion_de_fecha($parametros);

  $consulta .= ' ORDER BY FechaNoticia DESC';
  $consulta .= ' LIMIT ' . $por_pagina;

  return $consulta;
}

function obtener_noticias_de_la_bd($consulta) {
  $resultados = query($consulta);
  for ($noticias = array(); $fila = mysqli_fetch_assoc($resultados); $noticias[] = $fila);

  return $noticias;
}

function generar_restriccion_de_fecha($parametros) {
  if (isset($parametros['año'])) {
    if (isset($parametros['mes'])) {
      $fecha_inicio = $parametros['año'] . '-' . $parametros['mes']-- . '-31';
      $fecha_limite = $parametros['año'] . '-' . $parametros['mes']   . '-31';
    } else {
      $fecha_inicio = $parametros['año']-- . '-12-31';
      $fecha_limite = $parametros['año']   . '-12-31';
    }
  }

  $sql = '';
  if (isset($fecha_inicio)) {
    $sql = " AND FechaNoticia <= '$fecha_inicio' AND FechaNoticia > '$fecha_limite'";
  }

  return $sql;
}

echo obtener_noticias_en_json();