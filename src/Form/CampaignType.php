<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Campaign;
use App\Entity\Character;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class CampaignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["label" => "Nom de votre nouvelle campagne", 'required' => true])
            ->add('description')
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            // Les champs gameMaster et characters
            // ->add('gameMaster', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'username', // Assurez-vous d'utiliser un champ existant
            //     'multiple' => false,  // Seulement un Game Master pour la campagne
            //     'mapped' => false,    // Ne mappe pas ce champ à une propriété de Campaign
            // ])
            // ->add('characters', EntityType::class, [
            //     'class' => Character::class,
            //     'choice_label' => 'name', // Utilise un champ existant de Character
            //     'multiple' => true,  // Un ou plusieurs personnages
            //     'mapped' => false,   // Ne mappe pas ce champ à une propriété de Campaign
            // ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
    }
}
