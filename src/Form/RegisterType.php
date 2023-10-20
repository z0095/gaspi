<?php

namespace App\Form;

use App\dto\RoleToArrayTransformer as DtoRoleToArrayTransformer;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use App\Transformer\RoleToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class RegisterType extends AbstractType implements DataTransformerInterface
{
    public function transform($rolesArray)
    {
        
        return $rolesArray[0] ?? null;
    }

    public function reverseTransform($roleString)
    {
        
        return [$roleString];
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('roles', ChoiceType::class, [
            'required' => true,
            'label' => 'Vous souhaitez vous inscrire en tant que',
            'choices' => [
                'Restaurateur' => 'ROLE_RESTAURANT', 
                'Association' => 'ROLE_ASSOCIATION', 
            ],
            'multiple' => false,
            'expanded' => true,
        ]);
        $builder->get('roles')->addModelTransformer(new DtoRoleToArrayTransformer());


        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Saisissez Une adresse mail'
                ]
                ])


            ->add('password', PasswordType::class, [
                'required' => false,
                'label' => 'Mot de Passe',
                'attr' => [
                    'placeholder' => 'Saisissez Mot de passe'
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'required' => false,
                "mapped" => false,
                'label' => 'Confirmation de Mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez le Mot de passe'
                ]
            ])
            ->add('name', TypeTextType::class, [
                'required' => false,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisissez un nom'
                ]
            ])
            ->add('addresse', TypeTextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Saisissez une adresse',
                ]
            ])
            ->add('zipcode', TextType::class, [
                'required' => false,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Saisissez un code postal',
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Saisissez une ville',
                ]
            ])

            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Saisissez une description',
                ]
            ])

            ->add('phone', TextType::class, [
                'required' => false,  // Adjust as needed
                'label' => 'Téléphone',  
                'attr' => [
                    'placeholder' => 'Saisissez un numéro de téléphone',  
                ]
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
