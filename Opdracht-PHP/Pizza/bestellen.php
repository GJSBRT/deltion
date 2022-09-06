<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pizza di Mama</title>
        <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
        <link rel="icon" type="image/x-icon" href="https://gijs.eu/favicon.ico">
    </head>
    <body>
        <main class="container">
            <article>
                <hgroup>
                    <h1>Je bestelling is geplaatst! 🎉</h1>
                    <p>Wij zijn nu druk voor je bezig om jouw bestelling zo snel mogenlijk klaar te maken.</p>
                </hgroup>
            </article>
        </main>
    </body>
</html>
<?php
if (isset($_POST["name"])) {
    $naam = $_POST["name"];
    $street = $_POST["street"];
    $housenumber = $_POST["housenumber"];
    $zipcode = $_POST["zipcode"];
    $city = $_POST["city"];
    $deliveryTime = $_POST["deliveryTime"];
    $order = $_POST["orderData"];
    $delivery = $_POST["delivery"];
    $phoneNumber = $_POST["phonenumber"];
    $order = json_decode($order, true);
    $orderString = "**Telefoon:** {$phoneNumber}\n\n**Bestelling:**\n ";

    // Alle gewenste pizza mooi weergeven
    foreach ($order as $key=>$value){
        $orderString .= "{$value}x {$key}\n"; 
    }

    // Laten zien of de klant de pizza wilt laten bezorgen.
    if ($delivery) {
        $orderString .= "\n**Adres:**\n Bezorgen: ✅\n{$street} {$housenumber} {$zipcode} {$city}\n";
    } else {
        $orderString .= "\n**Adres:**\n Bezorgen: ❎";
    }

    // Bestelling door sturen naar de keuken. Hiervoor kan je gebruiken wat je wilt. Ik gebruik Discord webhooks want dat was op het moment het makkelijkste.
    $url = "https://discordapp.com/api/webhooks/87648763487264372/jhasgdjhagdyg3gHGASDjahjg-I4vz5WGUnkgQzeDKRyMo94-MNcufjeQU";
    $webhook = json_encode([
        "content" => "Een nieuwe bestelling is gemaakt!",
        "embeds" => [
            [
                "title" => "Bestelling voor {$naam} om {$deliveryTime}",
                "type" => "rich",
                "description" => $orderString,
                "footer" => [
                    "text" => "Pizza di Mama"
                ],
            ]
        ]

    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    $ch = curl_init();

    curl_setopt_array( $ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $webhook,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec( $ch );
    curl_close( $ch );

}
?>