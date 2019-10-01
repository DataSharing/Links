<?php

include __DIR__ . '/../config/db.php';
include __DIR__ . '/../class/db.class.php';

if (isset($_POST['shortcut'])) {
    $shortcut = htmlentities($_POST['shortcut']);
    $shortcutsNoAutorized = ['dashboard', 'shortcuts', 'users', 'login', 'profil'];
    $db = new db($db['username'], $db['password'], $db['hostname'], $db['dbname']);

    global $DB;

    $req = $DB->query('select id from shortcuts where shortcut="' . $shortcut . '"');
    $req->execute();

    if (!in_array($shortcut, $shortcutsNoAutorized)) {
        if ($req->rowCount() >= 1) {
            echo "<span class='badge badge-danger'><strong>" . $shortcut . "</strong> existe déjà!</span>";
        }
    } else { 
        echo "<span class='badge badge-danger'>Raccourci non autorisé!</span>";
    }
}
