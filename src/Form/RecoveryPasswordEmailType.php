<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 10.05.2018
 * Time: 22:27
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class RecoveryPasswordEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Email', EmailType::class);
    }
}