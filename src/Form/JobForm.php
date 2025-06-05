<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobCategory;
use App\Entity\JobType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('country')
            ->add('city')
            ->add('remoteAllowed')
            ->add('salaryRange')
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
            ])
            ->add('categories', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('jobType', EntityType::class, [
                'class' => JobType::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
