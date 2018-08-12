<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickModificationPictureDTOInterface;
use App\Domain\DTO\Trick\TrickModificationPictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickModificationPictureType extends AbstractType
{
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('description', TextareaType::class, [
				'required' => false,
				'label'    => 'Description',
			])
			->add('headPicture', CheckboxType::class, [
				'required' => false,
				'label' => 'Photo d\'en-tête',
			])
		;
	}

	public function configureOptions( OptionsResolver $resolver )
	{
		$resolver->setDefaults([
			'data_class' => TrickModificationPictureDTOInterface::class,
			'empty_data' => function (FormInterface $form) {
				return new TrickModificationPictureDTO(
					$form->get('description')->getData(),
					$form->get('picture')->getData()
				);
			},
		]);
	}

}
