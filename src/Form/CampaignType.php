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

class CampaignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["label" => "Nom de la catégorie", 'required' => true])
            ->add('description')
            // ->add('created_at', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updated_at', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('gameMaster', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('characters', EntityType::class, [
            //     'class' => Character::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
    }
}
