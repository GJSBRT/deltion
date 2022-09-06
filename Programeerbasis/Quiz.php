<?php

// Verzin een willekeurig getal
$randomNumber = rand(1, 100);

begin:
// Vraag om een getal
$input = readline("Voer een getal in of typ 'stop' om te stoppen: ");

if ($input == "stop") {
    // Stop als de speler wilt stoppen
    print "De quiz is gestopt. 😢\n";
} else {
    // Controleer of het getal gelijk is aan het getal dat de speler heeft ingevoerd
    $input = (int)$input;
    if ($input == $randomNumber) {
        print "Gefeliciteerd, je hebt het goed geraden! 🎉\n";
    } else {
        // Vertel de speler of het getal hoger of lager is
        if ($input > $randomNumber) {
            print "Het getal is kleiner dan $input 🔻\n";   
        } else {
            print "Het getal is groter dan $input 🔺\n";
        }
        // Begin opnieuw
        goto begin;
    }
}