<?php

namespace App\Application\FormFactory;

use App\Application\FormFactory\Interfaces\CommentModificationFormFactoryInterface;
use App\Domain\DTO\Comment\CommentModificationDTO;
use App\UI\Forms\Comment\AddCommentType;
use App\UI\Forms\Comment\CommentModificationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentModificationFormFactory implements CommentModificationFormFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * CommentModificationFormFactory constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Request $request): FormInterface
    {
        if (!is_null($request->query->get('action')) && !is_null($request->query->get('id'))) {
            $comment = $request->getSession()->remove('comment');

            if (!is_null($comment)) {
                if ($request->query->get('action') == 'modifier') {
                    $dto = new CommentModificationDTO($comment->getComment());

                    return $this->formFactory->create(CommentModificationType::class, $dto)
                                             ->handleRequest($request);
                }
            }
        }

        return $this->formFactory->create(AddCommentType::class)
                                 ->handleRequest($request);
    }
}
