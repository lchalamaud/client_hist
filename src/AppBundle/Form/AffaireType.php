<?php

namespace AppBundle\Form;

use AppBundle\Entity\Commercial;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AffaireType extends AbstractType
{
	/**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $option)
    {
    	
        $formBuilder
            ->add('Civilite', ChoiceType::class, array(
                'choices' =>  array(
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                )))
            ->add('Nom', TextType::class, array( 'attr' => array('placeholder' => 'NOM' )))
            ->add('Prenom', TextType::class, array( 'required' => false, 'mapped' => false, 'attr' => array('placeholder' => 'Prénom' )))
            ->add('Societe', TextType::class, array( 'attr' => array('placeholder' => 'Société' )))
            ->add('Telephone', TelType::class, array( 'attr' => array('placeholder' => 'Téléphone' )))
            ->add('EMail', TextType::class, array( 'attr' => array('placeholder' => 'exemple@mail.com' )))
            ->add('Rue', TextType::class, array( 'attr' => array('placeholder' => 'Rue' )))
            ->add('Complement', TextType::class, array( 'required' => false, 'attr' => array('placeholder' => 'Complément' )))
            ->add('CP', IntegerType::class, array( 'attr' => array('placeholder' => 'Code Postal' )))
            ->add('Ville', TextType::class, array( 'attr' => array('placeholder' => 'VILLE' )))
            ->add('Commercial', EntityType::class, array(
                'class' => 'AppBundle:Commercial',
                'choice_label' => 'acronyme'
            ))            
            ->add('Debut', DateType::class, array('widget' => 'single_text'))
            ->add('DevisType', ChoiceType::class, array(
                'choices' => array(
                    'Achat' => 'Achat',
                    'Location' => 'Loc.',
                    'Renseignement' => 'Rens.',
                )))
            ->add('SystemType', ChoiceType::class, array(
                'choices' => array(
                    'QuizzBox Entreprise' => 'Ent.',
                    'Version SSIAP - CQP' => 'SSIAP/CQP',
                    'QuizzBox Campus' => 'Campus',
                    'QuizzBox Education' => 'Educ.',
                    'QuizzBox Assemblée Générale' => 'AG',
                    'Autres' => 'Autres',
                )))
            ->add('NbController',   IntegerType::class, array( 'attr' => array( 'placeholder' => 'Nb Boitiers' )))
            ->add('Provenance', ChoiceType::class, array(
                'choices' => array(
                    'Recherche Internet (Google...)' => 'RI',
                    'Reseaux sociaux (Facebook)' => 'RS',
                    'Presse' => 'Presse',
                    'Recommandation' => 'Recommandation',
                    'Déja client' => 'Client',
                    'Autres' => 'Autres',
                )))
            ->add('Commentaire',      TextareaType::class, array('required' => false,))
            ->add('Id', IntegerType::class, array( 'required' => false, 'mapped' => false))
            ->add('Ajouter',      SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Affaire'
        ));
    }
}