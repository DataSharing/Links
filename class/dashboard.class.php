<?php

class Dashboard extends Controller
{
    public function __construct()
    { 
    }

    public function index()
    { 
        global $DB;
        $data = [];
        $where = ' where s.id_user='.$_SESSION['id'];
        if($_SESSION['admin']) $where = '';

        $requete = $DB->prepare('SELECT month(st.date_redirect) as mois, count(st.id) as nb from stats st 
        left join shortcuts s on s.id=st.id_url
        '.$where.' 
        group by month(date_redirect) order by date_redirect asc');
        $requete->execute();
        $stats = $requete->fetchall();
        foreach($stats as $stat)
        {
            $data['graph'][$stat['mois']] = $stat['nb'];
        }

        $requete_1 = $DB->prepare('select id from users');
        $requete_1->execute();
        $data['nb_users'] = $requete_1->rowCount();

        $requete_2 = $DB->query('select id from shortcuts');
        $requete_2->execute();
        $data['nb_shortcuts'] = $requete_2->rowCount();
        
        $requete_2 = $DB->query('select id from stats');
        $requete_2->execute();
        $data['nb_redirection'] = $requete_2->rowCount();

        $this->view('dashboard/index',$data);
    }
}
