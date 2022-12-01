<?php

function validateDate($date) {
    $format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function calcularDiferencia($date) {
    $hoy = new DateTime("now");
    $fecha_pasada = new DateTime($date);
    $diff = $hoy->diff($fecha_pasada);
    return $diff;
}

function mostrarDiferencia($date) {
    $diff = calcularDiferencia($date);
    $respuesta = "";
    if($diff->y > 0) {
        $respuesta = $diff->y;
        $respuesta .= $diff->y === 1 ? " año " : " años ";
    } else if($diff->m > 0) {
        $respuesta = $diff->m;
        $respuesta .= $diff->m === 1 ? " mes " : " meses ";
    } else if($diff->d > 0) {
        $respuesta = $diff->d;
        $respuesta .= $diff->d === 1 ? " día " : " días ";
    } else if($diff->h > 0) {
        $respuesta = $diff->h;
        $respuesta .= $diff->h === 1 ? " hora " : " horas ";
    } else if($diff->i > 0) {
        $respuesta = $diff->i;
        $respuesta .= $diff->i === 1 ? " minuto " : " minutos ";
    } else {
        $respuesta = $diff->s;
        $respuesta .= $diff->s === 1 ? " segundo " : " segundos ";
    }
    return $respuesta;
}