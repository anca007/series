<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class)
            ->add('firstAirDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true
            ])
            ->add('overview', TextareaType::class)
            ->add('poster', TextType::class)
            ->add('tmdbId', NumberType::class)
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'choice_label' => 'name',
                'query_builder' => function(SerieRepository $serieRepository){
                        $qb = $serieRepository->createQueryBuilder('s');
                        $qb->addOrderBy('s.name', 'ASC');
                        return $qb;
                }

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
