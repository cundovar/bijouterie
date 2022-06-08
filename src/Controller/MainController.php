<?php

namespace App\Controller; // app=src
//toutes les class doivent etre importé

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * 2 arguument
     * 1 la route (concatené apres localhost:8000-nomdedommaine.fr)
     * 2 le nom de la route(lien ect..)
     * 
     * @Route("/main", name="app_main")
     * 
     */
    public function index(): Response
    {

        // la methode render ()provenant d'abstrctController permet de ratacher une vue de sa fonction
        //2 argements :
        // 1er argument obligatoiure : nom du fichier de la vue ( avec son emplacement ) 
        // 2eme facultatif cest le tableau de donné provenant du controller a vehiculer sur la vue
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController et salut facundo',
        ]);
    }

    /**
     * creer une route pour aller sur la page principal ( localhost:8000)

     *annotation
     *function
     *view--> retourne une view 
     * --> creer le fichier home.html.twig ( a placer dans le dossier 
     *main)
     * -->heritage base html twig
     * --> block h1 et body
     * 
     * 
     * 
     * route de la page principal( acceuil)
     * @Route("/",name="home")
     */
public function home()
{
    $ageController=20;
    dump($ageController);// avec dump on peut tout checker
    // dump('tesst');die;
    // dd('tesst');


    return $this->render("main/home.html.twig",[
        //key=>value
        //key: nom de la variable array objet en twig
        // value : nom de la variabla array objet str du CONTROLLER
        "ageTwig"=>$ageController
    ] );
}


} // fermeture de la class rien en dessous
