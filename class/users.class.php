<?php

class Users extends Controller
{
    private $salt;

    public function __construct()
    {
        if ($_SESSION['admin'] == 0) header('location:index.php');
        include __DIR__ . '/../config/app.php';
        $this->salt = $app['salt'];
        $this->traitement();
    }

    public function traitement()
    {
        global $DB;

        if ($_POST) {
            $submit = isset($_POST['submit']) ? $_POST['submit'] : null;
            if ($submit == "add") {
                $login = isset($_POST['login']) ? htmlentities($_POST['login']) : null;
                $admin = isset($_POST['admin']) ? htmlentities($_POST['admin']) : null;
                $password = isset($_POST['password']) ? $_POST['password'] : null;
                $password_confirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : null;
                $check_duplicate = $DB->prepare('select id from users where login="' . $login . '"');
                $check_duplicate->execute();

                if (
                    $login != null
                    && $password != null
                    && $password_confirmation != null
                    && $check_duplicate->rowCount() == 0
                    && $password == $password_confirmation
                ) {
                    $password = sha1(sha1($password) . $this->salt);
                    $requete = $DB->prepare('INSERT INTO users(login,password,admin,date_modification,date_creation) values("' . $login . '","' . $password . '","' . $admin . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")');
                    if ($requete->execute()) {
                        $this->view('app/notif', 'L\'utilisateur a bien été ajouté');
                    }
                } else {
                    if ($check_duplicate->rowCount() >= 1) {
                        $this->view('app/error', 'L\'utilisateur existe déjà!');
                    } elseif ($password != $password_confirmation) {
                        $this->view('app/error', 'Les mots de passe ne sont pas identiques!');
                    } else {
                        $this->view('app/error', 'Tous les champs sont obligatoire!');
                    }
                }
            }

            if ($submit == "enregistrer" && isset($_GET['id'])) {
                $old_login = isset($_POST['old_login']) ? htmlentities($_POST['old_login']) : null;
                $login = isset($_POST['login']) ? htmlentities($_POST['login']) : null;
                $admin = isset($_POST['admin']) ? htmlentities($_POST['admin']) : null;
                $language = isset($_POST['language']) ? htmlentities($_POST['language']) : null;
                $check_duplicate = $DB->prepare('select id from users where login="' . $login . '"');
                $check_duplicate->execute();

                if (
                    $login != null
                    && $admin != null
                    && $language != null
                    && ($check_duplicate->rowCount() == 0 || $old_login == $login)
                ) {
                    $requete = $DB->prepare('UPDATE users SET login="' . $login . '",admin="' . $admin . '",language="'.$language.'" where id=' . intval($_GET['id']));
                    if ($requete->execute()) {
                        $this->view('app/notif', 'L\'utilisateur a bien été modifié');
                    }
                } else {
                    if ($check_duplicate->rowCount() >= 1) {
                        $this->view('app/error', 'L\'utilisateur existe déjà!');
                    }else {
                        $this->view('app/error', 'Tous les champs sont obligatoire!');
                    }
                }
            }

            if ($submit == "reset_password" && isset($_GET['id'])) {
                $password = isset($_POST['password']) ? $_POST['password'] : null;
                $password_confirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : null;

                if ($password != null && $password_confirmation != null && $password == $password_confirmation) {
                    $password = sha1(sha1($password) . $this->salt);
                    $requete = $DB->prepare('UPDATE users SET password="' . $password . '" where id=' . intval($_GET['id']));
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

            if ($submit == "supprimer" && isset($_GET['id'])) {
                $requete = $DB->prepare('delete from users where id=' . intval($_GET['id']));
                if ($requete->execute()) {
                    $requete_1 = $DB->query('delete from shortcuts where id_user='.intval($_GET['id']));
                    if($requete_1->execute()) header('location:users');
                } else {
                    $this->view('app/error', 'Erreur de suppression!');
                }
            }
        }
    }

    public function index()
    {
        if (isset($_GET['id'])) {
            $this->userForm(intval($_GET['id']));
        } else {
            $this->indexForm();
        }
    }

    public function indexForm()
    {
        global $DB;
        $where = '';
        if (isset($_GET['recherche'])) {
            $where = "where login like '%" . htmlentities($_GET['recherche']) . "%'";
        }

        $requete = $DB->prepare('select * from users ' . $where . ' order by id desc');
        $requete->execute();
        $data['users'] = $requete->fetchall();
        $this->view('users/index', $data);
    }

    public function userForm($id)
    {
        global $DB;
        $requete = $DB->prepare('select * from users where id=' . $id);
        $requete->execute();
        $data['user'] = $requete->fetch();
        $this->view('users/user', $data);
    }
}
