<?php

namespace App\Domain\DTO\Comment;

use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddCommentDTO implements CommentDTOInterface
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Les commentaires doivent avoir au moins {{ limit }} caractères."
     * )
     * @Assert\NotNull(message="Un commentaire doit être renseigné.")
     */
    public $comment;

    /**
     * AddCommentDTO constructor.
     * @param string $comment
     */
    public function __construct(?string $comment = '')
    {
        $this->comment = $comment;
    }
}
