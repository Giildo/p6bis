<?php

namespace App\Tests\Fixtures\Traits;

use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;

trait CommentFixtures
{
    /**
     * @var CommentInterface
     */
    protected $comment1;
    /**
     * @var CommentInterface
     */
    protected $comment2;
    /**
     * @var CommentInterface[]
     */
    protected $commentsList;

    public function constructComments()
    {
        $this->constructPicturesAndVideos();

        $this->comment1 = new Comment(
            'Commentaire 1',
            $this->mute,
            $this->johnDoe
        );

        $this->comment2 = new Comment(
            'Commentaire 2',
            $this->mute,
            $this->janeDoe
        );

        for ($i = 1 ; $i <= 15 ; $i++) {
            $this->commentsList[] = new Comment(
                'Commentaire r180_' . $i,
                $this->r180,
                $this->johnDoe
            );
        }
    }

    use PictureAndVideoFixtures;
}
