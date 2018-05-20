<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 15.05.2018
 * Time: 19:08
 */

namespace App\Form;

use App\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AnswerRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('next', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-default')))
            ->add('answers', EntityType::class, array(
            'multiple' => false,
            'expanded' => true,
            'class' => Answer::class,
            'query_builder' => function (AnswerRepository $er) use($options) {
                return $er->createQueryBuilder('a')
                    ->where('a.question = :id')
                    ->orderBy('a.text', 'ASC')
                    ->setParameter('id', $options['id']);
            },
            'choice_label' => 'text',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['id']);
    }
}