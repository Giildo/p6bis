<?php

namespace App\Tests\UI\Forms\Comment;

use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\UI\Forms\Comment\CommentModificationType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentModificationTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(CommentModificationType::class);

        $formData = [
            'comment' => 'Commentaire simulé.',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(CommentDTOInterface::class, $dto);
    }
}
