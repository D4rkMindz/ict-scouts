<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Zip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zip', null, [
                'label'     => 'PLZ',
                'required'  => true,
            ])
            ->add('city', null, [
                'label'     => 'Ort',
                'required'  => true,
            ]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zip::class,
            'empty_data' => function (FormInterface $form) {
            var_dump($form->getData());
                return new Zip(
                    $form->getData()['zip'],
                    $form->getData()['city']
                );
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_zip';
    }
}
