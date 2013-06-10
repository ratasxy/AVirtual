<?php

namespace OLabs\AVirtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type','choice', array(
                'choices'   => array(
                    'youtube' => 'Youtube',
                    'vimeo' => 'Vimeo',
                    'flickr' => 'Flickr',
                    'livestream' => 'Livestream',
                    'ustream' => 'Ustream',
                    'audio' => 'Audio',
                ),
                'required'  => false))
            ->add('url')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OLabs\AVirtBundle\Entity\Video'
        ));
    }

    public function getName()
    {
        return 'olabs_avirtbundle_videotype';
    }
}
