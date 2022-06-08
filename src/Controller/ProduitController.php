<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
//    FRONT OFFICE DES PRODUITS

// catalogue
// -->tous les produits

// fiches pruduit/{id}
// -->1 produit
   
   
  /**
   * @Route("/produit/catalogue",name="catalogue")
   */

   public function catalogue(ProduitRepository $repoProduitPublic):Response
   {
       $produits=$repoProduitPublic->findAll();
    //    dump($produits);
       return $this->render("produit/catalogue.html.twig",['produits'=>$produits] );
   }


   /**
    * @Route("/produit/fiche_produit/{id}",name="fiche_produit")
    */
    public function fiche_produit(Produit $produit)
    {
        return $this->render("produit/fiches_produits.html.twig",[ "produit"=>$produit] );
    }
   
   
   
   
   







    
}
