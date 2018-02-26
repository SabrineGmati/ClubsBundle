<?php

namespace ClubsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class ClubType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('description')
            ->add('tel')
            ->add('type', ChoiceType::class, array(
                    'label' => 'Type',
                    'choices' => array(
                        'Activités sportives' => 'sport',
                        'Activités artistiques' => 'artistique',
                        'Musique' => 'musique'
                    ),
                    'required' => true,
                    'multiple' => false,)
            )
            ->add('image', FileType::class, array('label' => 'Sélectionner une image de votre Club',
                'attr' => array('class' => 'filestyle', 'data-buttonName' => "btn-primary"), 'data_class' => null));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Club'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_club';
    }


}
