<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // deux request choisir http fondation

class AdminProduitController extends AbstractController
{
    /**
     * CRUD DE PRODUIT
     * 
     * /      ROUTE                    NOM DE ROUTE/function             VIEW
     * admin/produit/afficher        produit_afficher        produit_afficher.html.twig
     * admin/produit/ajouter         produit_ajouter
     * admin/produit/modifier         produit_modifier
     * admin/produit/supprimer        produit_supprimer
     * 
     * 
     * annotation
     * function
     * return view
     * creer un fichier
     *    ->heritage block title h1 body
     * 
     * dans la nav : onglet
     * 
     */
    /**
     * @Route("/admin/produit/afficher",name="produit_afficher")
     */

    public function produit_afficher(ProduitRepository $repoProduit): response
    {
        /*
         dans la paranthse de la function on instancie des objets issu de la class 
         syntaxe : class $objet
         c'est ce qu'on appelle une dépendance
         voir produitRpository.php
         */
        $produits = $repoProduit->findAll();/*SELECT*FROM produit*/
        dump($produits);

        return $this->render("admin_produit/produit_afficher.html.twig", ['produits' => $produits]);
    }


    /**
     * @Route("/admin/produit/ajouter",name="produit_ajouter")
     */
    public function produit_ajouter(Request $request, EntityManagerInterface $manager)
    {
        $produit = new Produit;
        dump($produit); // on observe que l'objet contient les propiété de l'entyty et qu'elles sont toutes null

        $form = $this->createForm(ProduitType::class, $produit, ['ajouter' => true]);

        //definir la class qui contient les infos
        // crrer le html du formulaire

        // pour creer un formulaire on utlise la methode createform() provenant e AbstractController
        //arguement :
        //1er obligatoire =le nom de la class contenan,t le builder
        //2eme obligatoiure = l'objet issu de la class( entity) dont la class..Type est issue
        //3eme facultatif = tableau des options

        //==> resultat : $form est un objet


        /*
        traitement du formulaire 
        $request -> request($_POST super global)
        $request -> query($_GET super global)
        */
        $form->handleRequest($request);

        /* si le formulaire est soumis clic sur le bouton submitet si le leformualire est valide  contraintes, les conditions*/
        if ($form->isSubmitted() and $form->isValid()) {
            //    dd($produit);/


            $imageFile = $form->get('image')->getData();
            // dd($imageFile);
            /*
            dans notre projet l'image n'est pas obligatoire
            observation
            $imageFIles peut etre NULL ca veut dire aucun upload
            $imageFiles est un objet issu de la class uploadedFile ca veut dire upload
            
            */
            // si il y a image upload le traitement se situ dans la condition
            if ($imageFile) {
                //1ere etape definir nom de l'image
                $nomImage = date("YmdHis") . "-" . uniqid() . "-" . "" . $imageFile->getclientOriginalExtension();
                // dd($nomImage);

                //2eme etatpe deplacer le fichier image dans le dossier public
                $imageFile->move(

                    $this->getParameter("imageProduit"),
                    $nomImage

                );

                // 3eme etape enregistrer dans l'objet $produit a la propriété image le nom du fichier
                $produit->setImage($nomImage);
            }






            /* la class entityManagerinetrface permet les requete insert into update delete*/
            //la methode remove( ) permet de supprimer 
            $produit->setDateAt(new \DateTimeImmutable('now')); // mettre avant persist
            $manager->persist($produit);
            $manager->flush(); //ancienne methode 
            // $repoProduit->add($produit);nouvelle methode

            return $this->redirectToRoute('produit_afficher');
            //    anti slashe car datetimeimmutable not made in class symphony mais une class PHP donc pour l'importer on use antislash, mettre la date de maintanant  
        }

        // creer notification
        // la methode addFlash() provenant de la class abstractController permet d'afficher un message sur la navigateur(twig) cree depus le controller
        //2 arguments 
        //1 le nom du flash
        // 2 me message


        $this->addFlash("success", "le produit N° " . $produit->getId() . " a bien été ajouté");




        //faire une redirection apres avoir cliquer ajouter, la methode redirectToRoute de la class AbstractController permet de rediriger  sur une autre route 
        // l'argument obligatoire : le nom de la route !
        // facultatif : le tableau des paramtres 
        //  redirectToRoute et la function twig path  permette de rediriger ( un en twig l'autre en php)






        return $this->render('admin_produit/produit_ajouter.html.twig', ["formProduit" => $form->createView()]);
    }

    /**
     * @Route("/admin/produit/voir/{id}",name="produit_voir")
     */
    //   public function produit_voir($id, ProduitRepository $repoProduit){
    // SELECT *FROM produit selectionne tous les produits => findAll()
    // SELECT*FROM produit WHERE id selctionne la ligne de l'id => find($id)

    // $produit =$repoProduit->find($id);
    // dd($produit);

    // return $this->render('admin_produit/produit_voir.html.twig',[
    // "produit"=>$produit // afficher le produit
    // ] );

    //   } 

    //autre maniere de faire on simplifi au lieu de laire en deux temps on le fait en 1

    public function produit_voir(Produit $produit)
    {
        return $this->render('admin_produit/produit_voir.html.twig', [
            "produit" => $produit

            /**
         * le parametre id dals l'url est injecté dans l'objet, a la propriété portant la même dénominatin
         */
        ]);
    }






    /**
     * @Route ("/admin/produit/modifier/{id}",name="produit_modifier")
     */
    public function produit_modifier(Produit $produit, request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ProduitType::class, $produit,['modifier'=>true] );

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $imageFile = $form->get('imageUpdate')->getData();
            if ($imageFile) {
                // 1e étape : Définir le nom de l'image 
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();
                // 20220608145004-62a09afc38ee9.jpg
                // 20220608145105-62a09b39c02a2.jpg
                //dd($nomImage);

                // 2e étape : Déplacer le fichier image dans le dossier public
                $imageFile->move(
                    $this->getParameter("imageProduit"),
                    $nomImage
                );
                /*
                La méthode move() permet de déplacer un fichier dans le projet
                2 argument :
                emplacement
                le nom attribué au fichier

                L'emplacement :
                La méthode getParameter() permet d'aller rechercher dans le fichier config/services.yaml le nom du paramètre placé comme argument de la fonction
                (fichier services.yaml) ====>
                    parameters:
                        imageProduit: '%kernel.project_dir%/public/images/produit'

                        %kernel.project_dir% ==> le nom du dossier (ici c'est bijouterie)
            */

                // 3e étape : Enregistrer dans l'objet $produit à la propriété image le nom du fichier
                $produit->setImage($nomImage);
            }
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash("success", "le produit" . $produit->getId() . "  a bien été modifié");
            return $this->redirectToRoute("produit_afficher");
        }
        return $this->render(
            'admin_produit/produit_modifier.html.twig',
            [
                "produit" => $produit,
                "formProduit" => $form->createView()
            ]
        );
    }
    /**
     * @Route ("/admin/produit/supprimer/{id}",name="produit_supprimer")
     */
    public function produit_supprimer(Produit $produit, EntityManagerInterface $manager)
    {
        // dump($produit);
        $idProduit = $produit->getId(); // pour concerver l'id pour le montrer dans addflash
        $manager->remove($produit);
        $manager->flush();
        // dd($produit);

        $this->addFlash("success", "le produit N°" . $idProduit() . " a bien été effacé.");
        return $this->redirectToRoute('produit_afficher');
    }
}
