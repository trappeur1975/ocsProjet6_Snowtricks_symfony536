<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isChecked', CheckboxType::class, [
                // 'label' => $this->get('pictureFileName')
                'label' => 'supprimer'
            ])
            // ->add('pictureFileName')
            // ->add(
            //     'tata',
            //     CheckboxType::class,
            //     ['mapped' => false]
            // )
            // ->add('deletePicture', ChoiceType::class, [
            //     // 'choices' => [
            //     //     'In Stock' => true,
            //     //     'Out of Stock' => false,
            //     // ],
            //     'label'    => 'Supprimer cette Picture',
            //     // 'choice_label' => 'pictureFileName',
            //     'mapped' => false,
            //     'required' => false,
            // ])

            // ->add('pictureFileName', FileType::class, [
            //     'label' => false,
            //     'data_class' => null,
            //     'attr' => [
            //         'placeholder' => 'Modifier ou ajouter une image',
            //     ],
            // ])

            // ->add('trick', EntityType::class, [
            //     'class' => Trick::class,
            //     'choice_label' => 'name'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
