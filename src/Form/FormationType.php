<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use DateTime;

/**
 * Formulaire pour la gestion des entités Formation.
 *
 * Ce formulaire permet de créer ou de modifier une formation,
 * en incluant des champs pour la date de publication, le titre, la description,
 * l'identifiant vidéo YouTube, la playlist associée et les catégories.
 */
class FormationType extends AbstractType
{
    /**
     * Construit le formulaire pour l'entité Formation.
     *
     * Ajoute les champs 'publishedAt', 'title', 'description', 'videoId',
     * 'playlist', 'categories' et un bouton de soumission 'submit'.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'data' => isset($options['data']) && $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new DateTime('now'),
            ])
            ->add('title')
            ->add('description', null, [
                'required' => false,
            ])
            ->add('videoId')
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => function (Playlist $playlist) {
                    return $playlist->getId() . ' - ' . $playlist->getName();
                },
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => function (Categorie $category) {
                    return $category->getId() . ' - ' . $category->getName();
                },
                'multiple' => true,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    /**
     * Configure les options par défaut pour ce type de formulaire.
     *
     * Définit la classe de données associée à ce formulaire comme étant Formation.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
