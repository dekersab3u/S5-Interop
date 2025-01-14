<?php

const HTTP_OPTS = array(
    'http' => array(
        'proxy' => 'tcp://127.0.0.1:8080',
        'request_fulluri' => true
    ),
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);

function query($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXY, 'www-cache:3128');
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    $response = curl_exec($ch);
    $context = stream_context_create(HTTP_OPTS);
    if ($response === false) {
        throw new Exception("Erreur lors de la requête : " . $url);
    }

    return $response;
}

function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    return $_SERVER['REMOTE_ADDR'];
}

function geolocateIp($ip) {
    $data = query("http://ip-api.com/json/{$ip}");
    if ($data && isset($data['lat'], $data['lon'], $data['city'])) {
        return [
            'latitude' => $data['lat'],
            'longitude' => $data['lon'],
            'city' => $data['city']
        ];
    }
    return [
        'latitude' => 48.693722,
        'longitude' => 6.184417,
        'city' => 'Nancy'
    ];
}

function getWeatherData($latitude, $longitude) {
    $response = query("https://www.infoclimat.fr/public-api/gfs/xml?_ll={$latitude},{$longitude}&_auth=AxlUQwB%2BACJXelBnA3UCK1I6ADVeKFVyAHxXNA5rAn8Aa1c2BWUAZgBuB3oDLFJkAi9QMwkyUGADaAB4CXtXNgNpVDgAawBnVzhQNQMsAilSaABkXmdVbwBiVy8OfAJpAGJXLQVkAGAAbwd7AzJSZwIzUC4JN1BhA38AeAllVzMDY1Q2AGsAY1c6UDMDMQI%2FUn4Af15nVWoAa1c1DjICZwBlVzIFMgBrAGcHZANmUmECL1A1CTFQaQNgAG8JY1c8A2hULwB8ABtXS1AvA3MCdFI0ACZefFU4AD1XZA%3D%3D&_c=451aa00841cdb4fc82fe2d0072f1b5f4");
    if ($response === null) {
        die("<p>Erreur : La requête météo n'a pas pu être effectuée</p>");
    }
    return $response;
}

$clientIp = getClientIp();
$geoData = geolocateIp($clientIp);
$latitude = $geoData['latitude'];
$longitude = $geoData['longitude'];

$city = $geoData['city'];

$weatherXml = getWeatherData($latitude, $longitude);

if (!$weatherXml) {
    die("<p>Données vides</p>");
}

$xml = new DOMDocument();
if (!$xml->loadXML($weatherXml)) {
    die("<p>Les données météo récupérées sont invalides.</p>");
}

$xsl = new DOMDocument();
if (!$xsl->load('weather.xsl')) {
    die("<p>Erreur XSL.</p>");
}

$proc = new XSLTProcessor();
$proc->importStylesheet($xsl);
$weatherHtml = $proc->transformToXML($xml);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atmosphère</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
</head>
<body>

    <div id="github">
        <p>Lien github : <a href="https://github.com/dekersab3u/S5-Interop" target="_blank">https://github.com/dekersab3u/S5-Interop</a> </p>
    </div>

    <h1>Météo et Circulation</h1>
    <p>Localisation détectée : <?php echo htmlspecialchars($city); ?></p>
    <?php echo $weatherHtml; ?>

    <div id="map">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const latitude = <?php echo $latitude; ?>;
        const longitude = <?php echo $longitude; ?>;

        const map = L.map('map').setView([latitude, longitude], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup('Vous êtes ici.')
            .openPopup();
    </script>
    </div>
</body>
</html>
