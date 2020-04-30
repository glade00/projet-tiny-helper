<?php

namespace App\Form;

use App\Entity\Sites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom de l\'outil :', 'help' => '40 caractères max.'])
            ->add('description', TextareaType::class, ['label' => 'Description de l\'outil :'])
            // ->add('publishedAt')
            ->add('illustration', FileType::class, [

                'label' => 'Illustration :',
                'label_attr' => ['data-browse' => 'parcourir ...'],
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'S\'il vous plaît mettez une image valide',
                    ])
                ],
            ])
            ->add('link', TextType::class, ['label' => 'Lien de l\'outil :'])

            ->add('category', null, [
                'label' => 'Mots clés :',
                'required' => false,
                'multiple' => false,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sites::class,
        ]);
    }
}
