<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small',
                'attr' => [
                    'class' => 'form-control'
                ],           
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
