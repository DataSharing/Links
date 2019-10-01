<?php

Class Profil extends Controller {

    private $salt;
    
    public function __construct()
    {
        include __DIR__ . '/../config/app.php';
        $this->salt = $app['salt'];
        $this->traitement();
    }
    
    public function traitement()
    { 
        global $DB;
        
        if($_POST)
        {
            $submit = isset($_POST['submit']) ? $_POST['submit'] : null;

            if ($submit == "enregistrer" && isset($_SESSION['id'])) {
                $old_login = isset($_POST['old_login']) ? htmlentities($_POST['old_login']) : null;
                $login = isset($_POST['login']) ? htmlentities($_POST['login']) : null;
                $language = isset($_POST['language']) ? htmlentities($_POST['language']) : null;
                $check_duplicate = $DB->prepare('select id from users where login="' . $login . '"');
                $check_duplicate->execute();

                if (
                    $login != null
                    && $language != null
                    && ($check_duplicate->rowCount() == 0 || $old_login == $login)
                ) {
                    $requete = $DB->prepare('UPDATE users SET login="' . $login . '",language="'.$language.'" where id=' . intval($_SESSION['id']));
                    if ($requete->execute()) {
                        $_SESSION['language'] = $language;
                        $this->view('app/notif', 'Les informations ont bien été modifiées');
                    }
                } else {
                    if ($check_duplicate->rowCount() >= 1) {
                        $this->view('app/error', 'L\'utilisateur existe déjà!');
                    }else {
                        $this->view('app/error', 'Tous les champs sont obligatoire!');
                    }
                }
            }

            if ($submit == "reset_password" && isset($_SESSION['id'])) {
                $password = isset($_POST['password']) ? $_POST['password'] : null;
                $password_confirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : null;

                if ($password != null && $password_confirmation != null && $password == $password_confirmation) {
                    $password = sha1(sha1($password) . $this->salt);
                    $requete = $DB->prepare('UPDATE users SET password="' . $password . '" where id=' . intval($_SESSION['id']));
                    if ($requete->execute()) {
                        $this->view('app/notif', 'Le mot de passe a bien été réinitialisé');
                    }
                } else {
                    if ($password != $password_confirmation) {
                        $this->view('app/error', 'Les mots de passe ne sont pas identiques!');
                    } else {
                        $this->view('app/error', 'Tous les champs sont obligatoire!');
                    }
                }
            }
        }
    }

    public function index()
    {
        global $DB;
        $requete = $DB->prepare('select * from users where id=' . $_SESSION['id']);
        $requete->execute();
        $data['user'] = $requete->fetch();
        $this->view('profil/index',$data);
    }
}