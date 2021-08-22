<?php

namespace App\Form;

use App\Entity\Pool;
use App\Entity\User;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('pool', EntityType::class, [
                'class' => Pool::class,
                'choice_label' => 'name'
            ])
            ->add(
                'user',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'nickname'
                ]
            )
            ->add('pictures', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
