<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoEasyadminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('videoFileName', TextType::class, [
                // ->add('videoFileName', UrlType::class, [
                'required' => true,
                'label' => 'source video (url/nom)',
                // 'attr' => ['novalidate' => 'novalidate'],
            ])
            // ->add('addVideo', UrlType::class, [
            //         'mapped' => false,
            //         'required' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
