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
                    'business.modal.MR' => 'M.',
                    'business.modal.MRS' => 'Mme',
                )))
            ->add('Nom', TextType::class, array( 'attr' => array('placeholder' => 'business.modal.lastName' )))
            ->add('Prenom', TextType::class, array( 'required' => false, 'mapped' => false, 'attr' => array('placeholder' => 'business.modal.firstName' )))
            ->add('Societe', TextType::class, array(
                'label' => 'business.modal.society',
                'attr' => array('placeholder' => 'business.modal.society' )
            ))
            ->add('Telephone', TelType::class, array(
                'label' => 'business.modal.phone',
                'attr' => array('placeholder' => 'business.modal.phone' )
            ))
            ->add('EMail', TextType::class, array(
                'label' => 'business.modal.mail',
                'attr' => array('placeholder' => 'business.modal.mail_exemple' )
            ))
            ->add('Rue', TextType::class, array(
                'label' => 'business.modal.address',
                'attr' => array('placeholder' => 'business.modal.street' )
            ))
            ->add('Complement', TextType::class, array(
                'label' => 'business.modal.complement',
                'required' => false,
                'attr' => array('placeholder' => 'business.modal.complement' )
            ))
            ->add('CP', IntegerType::class, array( 'attr' => array('placeholder' => 'business.modal.pc' )))
            ->add('Ville', TextType::class, array( 'attr' => array('placeholder' => 'business.modal.city' )))
            ->add('Commercial', EntityType::class, array(
                'label' => 'business.modal.commercial',
                'required' => false,
                'class' => 'AppBundle:Commercial',
                'choice_label' => 'acronyme'
            ))            
            ->add('Debut', DateType::class, array(
                'label' => 'business.modal.begin',
                'widget' => 'single_text'
            ))
            ->add('DevisType', ChoiceType::class, array(
                'label' => 'business.modal.devisType',
                'choices' => array(
                    'business.modal.buy' => 'Achat',
                    'business.modal.rent' => 'Loc.',
                    'business.modal.information' => 'Rens.',
                )))
            ->add('SystemType', ChoiceType::class, array(
                'choices' => array(
                    'business.modal.qbBusiness' => 'Ent.',
                    'business.modal.qbSSIAP/CQP' => 'SSIAP/CQP',
                    'business.modal.qbCampus' => 'Campus',
                    'business.modal.qbEduc' => 'Educ.',
                    'business.modal.qbGeneralMeeting' => 'AG',
                    'business.modal.others' => 'Autres',
                )))
            ->add('NbController',   IntegerType::class, array( 'attr' => array( 'placeholder' => 'business.modal.nb' )))
            ->add('Provenance', ChoiceType::class, array(
                'label' => 'business.modal.origin',
                'choices' => array(
                    'business.modal.internetSearch' => 'RI',
                    'business.modal.socialNetwork' => 'RS',
                    'business.modal.presse' => 'Presse',
                    'business.modal.recommendation' => 'Recommandation',
                    'business.modal.alreadyCustomer' => 'Client',
                    'business.modal.others' => 'Autres',
                )))
            ->add('Commentaire',      TextareaType::class, array(
                'label' => 'business.modal.comment',
                'required' => false,
            ))
            ->add('Id', IntegerType::class, array(
                'label' => false,
                'required' => false, 'mapped' => false
            ))
            ->add('Ajouter',      SubmitType::class, array( 'label' => 'button.add'))
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