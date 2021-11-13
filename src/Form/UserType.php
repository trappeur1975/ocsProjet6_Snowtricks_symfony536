<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname')
            ->add('email')

            ->add('password')
            // ->add('password', PasswordType::class, [
            //     'attr' => ['placeholder' => 'entrer votre mot de passe']
            // ])
            // ->add('password', PasswordType::class, [
            //     'always_empty' => false,
            //     'required' => false,
            //     'attr' => ['placeholder' => 'entrer votre mot de passe']
            // ])

            ->add('newPictures', FileType::class, [
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '400k',
                        'mimeTypesMessage' => 'les types de photo autorisés sont du png ou du jpg',
                        'maxSizeMessage' => 'votre fichier doit etre infèrieur à  {{ limit }} Mo',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                        ],
                    ])

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
