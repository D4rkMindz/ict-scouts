<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('givenName', null, [
                'label'     => 'Vorname',
                'required'  => true,
            ])
            ->add('familyName', null, [
                'label'     => 'Name',
                'required'  => true,
            ])
            ->add('street', null, [
                'label'     => 'Adresse',
                'required'  => true,
            ])
            ->add('addressExtra', null, [
                'label'     => 'Adresszusatz',
                'required'  => false,
            ])
            ->add('zip', null, [
                'label'     => 'Ort',
                'required'  => false,
            ])
            ->add('province', null, [
                'label'     => 'Kanton',
                'required'  => true,
            ])
            ->add('phone', null, [
                'label'     => 'Telefon',
                'required'  => false,
            ])
            ->add('mail', EmailType::class, [
                'label'     => 'E-Mail',
                'required'  => false,
            ])
            ->add('birthDate', BirthdayType::class, [
                'label'     => 'Geburtstag',
                'required'  => false,
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
            'data_class' => Person::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_person';
    }
}
