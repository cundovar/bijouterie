<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Matiere;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class,["label"=>"titre du produit",
            "attr"=>[
                "placeholder"=>"saisir un titre",
                "required"=>false,
                /*,
                exemple : on peut avec class style, le tout dans un tableau et c'est le 3eme element de la methode Type
                "class"=>"border border-danger bg-light",
                "style"=>"box-shadow 1px"
                */
            ],
            "label_attr"=> [/*tableau de attribu de la balise label*/ "class"=>"text-primary" ],
                "row_attr"=>["id"=>"titreBlock"],//tableau des attribus de la balise div contenant label/input
                
                "constraints"=>[
                    new NotBlank([
                        "message"=>"saisir un titre2"
                    ] ),
                    new Length([
                        "min"=>4,
                        "max"=>15,
                        "minMessage"=>"4 mini",
                        "maxMessage"=>"15 max"
                    ] )
                ]
             ] )

            ->add('prix',MoneyType::class,["required"=>false,
                "currency"=>"EUR","label"=>"prix du produit","attr"=>["placeholder"=>"saisir un prix"]
            ] )


            ->add('description',TextareaType::class, [
                "required"=>false,
             "attr"=>["row"=>8] ]
            )
            ->add('categorie',EntityType::class,[// entity type c'es pour les relations
                "class"=>Categorie::class,// la class (entity)
                "choice_label"=>"nom",// nom de la propiété
                "placeholder"=>"ecrire un truc",
                "required"=>false

                // "expanded"=>true,
                // transform en radio input html formulaire
                
            ] )
            ->add('marque',EntityType::class, [
                "class"=>Marque::class,
                "choice_label"=>"nom"
                

            ])
            ->add('matiere',EntityType::class,[
                "class"=>Matiere::class,
                "choice_label"=>"nom",
                "required"=>false,
                "multiple"=>true // car matiere est un tableau car je peux definir plusieir matiers pour 1 elements
                //"expanded"=>true // premet d'afficher des checkbox ( creation radio =>voir formulaire html)
            ]);
            

            // ->add('image')

            //   ->add("ajouter",SubmitType::class) ajouter boutou submit si balise form dans produit_ajouter.html est en form "brut "{{form(formProduit)}}" sans start sans end
            if($options['ajouter'] ){

            $builder->add('image',FileType::class,[
            "required"=>false,
            "attr"=>[
                'onchange'=> "loadFile(event)"]    
                 ] ) ;
            }

            if($options['modifier'] ){

                $builder->add('imageUpdate',FileType::class,[
                    "required"=>false,
                    "mapped"=>false, // qui n'est pas dens l'entity 
                    "attr"=>[
                        'onchange'=> "loadFile(event)"]    
                         ] ) ;


            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'ajouter'=>false,
            'modifier'=>false
        ]);
    }
}
