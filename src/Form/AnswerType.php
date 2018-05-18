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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('users', EntityType::class, array(
            'multiple' => false,
            'expanded' => true,
            'class' => Answer::class,
            'query_builder' => function (AnswerRepository $er) use($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.question =1')
                    ->orderBy('u.text', 'ASC');
            },
            'choice_label' => 'text',
        ));
    }
}