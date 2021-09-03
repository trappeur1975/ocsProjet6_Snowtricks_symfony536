<?php

namespace App\Form;

use App\Entity\Pool;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\PictureType;
use Doctrine\ORM\EntityRepository;
use App\Repository\PictureRepository;
// use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // on recupere notre entity trick
        $trick = $options['data'];
        // dd($options['data']->getId())
        // dd($options['data']);

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
            ->add('newPictures', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])

            // ->add('pictures', CollectionType::class, [
            //     'entry_type' => PictureType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
            ->add('pictures', EntityType::class, [
                'class' => Picture::class,
                'choice_label' => 'pictureFileName',
                'query_builder' => function (PictureRepository $pictureRepository) use ($trick) {
                    return $pictureRepository->findPicturesTrick($trick);
                },
                'choice_attr' => function ($choice, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return [];
                },
                'multiple' => true
            ])
            // ->add('pictures', CheckboxType::class, [
            //     // 'class' => Picture::class,
            //     'label' => 'pictureFileName',
            //     // 'query_builder' => function (PictureRepository $pictureRepository) use ($trick) {
            //     //     return $pictureRepository->findPicturesTrick($trick);
            //     // },
            //     // 'attr' => [
            //     //     'select' => ' '
            //     // ],
            //     // 'multiple' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
