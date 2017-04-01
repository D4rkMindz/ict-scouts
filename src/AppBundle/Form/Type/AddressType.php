<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', null, [
                'label'     => 'Adresse',
                'required'  => true,
            ])
            ->add('addressExtra', null, [
                'label'     => 'Adresszusatz',
                'required'  => false,
            ])
//            ->add('zip', ZipType::class, [
//                'label'     => false,
//                'required'  => true,
//            ])
            ->add('province', null, [
                'label'     => 'Kanton',
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
            'data_class' => Address::class,
            'empty_data' => function (FormInterface $form) {
                var_dump($form->getData());
                return new Address(
                    $form->getData()['street'],
                    $form->getData()['zip'],
                    $form->getData()['province'],
                    $form->getData()['addressExtra']
                );
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_address';
    }
}
