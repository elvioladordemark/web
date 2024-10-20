<?php
// El URL del archivo M3U8 original y el referer que requiere
$original_url = 'https://anonmedia.eu/amc/index.m3u8';
$referer = 'https://jalynrabei.com/';

// Configuración de la solicitud con el Referer correcto
$options = [
    "http" => [
        "header" => "Referer: " . $referer . "\r\n"
    ]
];

// Crear un contexto de flujo con las opciones de encabezado
$context = stream_context_create($options);

// Obtener el contenido del archivo M3U8 original
$content = file_get_contents($original_url, false, $context);

// Verificar si el contenido fue recuperado correctamente
if ($content === false) {
    // Si no se pudo recuperar el contenido, mostrar un error
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error al recuperar el archivo M3U8";
    exit;
}

// Establecer los encabezados para devolver el archivo M3U8 como respuesta
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: inline; filename=\"proxy.m3u8\"");

// Mostrar el contenido del archivo M3U8
echo $content;
?>
