<?php

namespace App\Controller\App\Form;

use App\Controller\App\DTO\ProductsQueryDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsQueryForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('category')
            ->add('description')
            ->add('fromWeight')
            ->add('toWeight')
            ->add('orderBy')
            ->add('direction')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductsQueryDTO::class,
            'allow_extra_data' => true,
        ]);
    }
}