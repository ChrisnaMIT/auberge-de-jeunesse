<?php

namespace App\Form;

use App\Entity\Amenity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmenityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la commodité',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Ex: Wi-Fi, Salle de bain, Climatisation...'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amenity::class,
        ]);
    }
}
