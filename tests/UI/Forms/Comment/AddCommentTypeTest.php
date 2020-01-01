<?php

namespace App\Tests\UI\Forms\Comment;

use App\Domain\DTO\Interfaces\Comment\AddCommentDTOInterface;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\UI\Forms\Comment\AddCommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class AddCommentTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(AddCommentType::class);

        $formData = [
            'comment' => 'Commentaire simulé.',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(CommentDTOInterface::class, $dto);
    }
}
