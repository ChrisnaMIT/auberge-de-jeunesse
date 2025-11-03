<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
                'label' => 'Chambre',
                'placeholder' => 'Sélectionner une chambre',
                'attr' => ['class' => 'form-control'],
            ])

            ->add('checkIn', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => "Date d'arrivée",
            ])

            ->add('checkOut', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => "Date de départ",
            ])

            ->add('bedsBooked', IntegerType::class, [
                'label' => 'Nombre de lits réservés',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                ],
            ])

            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la réservation',
                'choices' => [
                    'Confirmée' => 'confirmed',
                    'En attente' => 'pending',
                    'Annulée' => 'cancelled',
                ],
                'attr' => ['class' => 'form-select'],
            ])

            ->add('totalPrice', NumberType::class, [
                'label' => 'Prix total (€)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Calculé automatiquement',
                    'readonly' => true
                ],
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
