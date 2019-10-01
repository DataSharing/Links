<?php

class Login extends Controller
{
    Private $salt;

    public function __construct()
    {
        include __DIR__ . '/../config/app.php';
        $this->salt = $app['salt'];
    }

    public function index()
    {
        $this->traitement();
        $this->view('login/index');
    }

    public function traitement()
    {
        global $DB;

        if ($_POST) 
        {
            $username = isset($_POST['username']) ? htmlentities($_POST['username']) : null;
            $password = isset($_POST['password']) ? sha1(sha1($_POST['password']).$this->salt) : null;

            if($username != null && $password != null)
            {
                $requete = $DB->prepare('select id,admin,language from users where login="'.$username.'" and password="'.$password.'"');
                $requete->execute();

                if($requete->rowCount() == 1)
                {
                    $row = $requete->fetch();
                    $_SESSION['admin'] = false;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['language'] = $row['language'];
                    if($row['admin'] == 1) $_SESSION['admin'] = true;
                    header('location:index.php');
                }else{
                    $this->view('login/error');
                }
            }
        }
    }
}
