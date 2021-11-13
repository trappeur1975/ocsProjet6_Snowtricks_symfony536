<?php

namespace App\Form;

use App\Entity\Pool;
// use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Picture;
// use App\Form\PictureType;
// use Doctrine\ORM\EntityRepository;
use App\Repository\VideoRepository;
use App\Repository\PictureRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
// use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we get our entity trick 
        $trick = $options['data'];

        $builder
            ->add('name')
            ->add('description')
            ->add('pool', EntityType::class, [
                'class' => Pool::class,
                'choice_label' => 'name'
            ])

            ->add('newPictures', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All([
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
                        ],
                    ]),
                ]
            ])
            ->add('pictures', EntityType::class, [
                'class' => Picture::class,
                'label' => 'Images du trick a supprimer',
                'choice_label' => 'pictureFileName',
                'query_builder' => function (PictureRepository $pictureRepository) use ($trick) {
                    return $pictureRepository->findPicturesTrick($trick);
                },
                'mapped' => false,
                'multiple' => true,
                'expanded' => true, //to put in checkbox 
                // 'by_reference' => false, // pour l'enregistrement des infos pour ne pas rechercher une methode setter mais une methode add
            ])
            ->add('newVideo', UrlType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('videos', EntityType::class, [
                'class' => Video::class,
                'label' => 'Video du trick a supprimer',
                'choice_label' => 'VideoFileName',
                'query_builder' => function (VideoRepository $videoRepository) use ($trick) {
                    return $videoRepository->findVideosTrick($trick);
                },
                'mapped' => false,
                'multiple' => true,
                'expanded' => true, //pour mettre en chekbox
                // 'by_reference' => false, // pour l'enregistrement des infos pour ne pas rechercher une methode setter mais une methode add
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
