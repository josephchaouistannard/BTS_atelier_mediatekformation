<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Formulaire pour la gestion des entités Categorie.
 *
 * Ce formulaire permet de créer ou de modifier une catégorie,
 * en se concentrant sur le champ 'name' de l'entité Categorie.
 */
class CategorieType extends AbstractType
{
    /**
     * Construit le formulaire pour l'entité Categorie.
     *
     * Ajoute les champs 'name' et un bouton de soumission 'submit'.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    /**
     * Configure les options par défaut pour ce type de formulaire.
     *
     * Définit la classe de données associée à ce formulaire comme étant Categorie.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
