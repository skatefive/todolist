<?php

namespace Ens\JobeetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            //->add('status')
            ->add('due_date')
        ;

        $builder->add('status', 'choice', array(
            'choices'   => array('1' => 'Avatud', '2' => 'Pooleli', '3' => 'Lõpetatud'),
            'required'  => true,
            'empty_value' => 'Choose an option',
        ));        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ens\JobeetBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ens_jobeetbundle_task';
    }
}
