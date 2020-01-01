<?php

namespace App\Tests\Application\Helpers;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Application\Helpers\PaginationHelper;
use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PaginationHelpersTest extends TestCase
{
    /**
     * @var TrickRepository|MockObject
     */
    private $trickRepository;

    /**
     * @var PaginationHelperInterface
     */
    private $paginationHelper;

    public function setUp()
    {
        $this->trickRepository = $this->createMock(TrickRepository::class);

        $this->paginationHelper = new PaginationHelper();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfPagingIsTooBig()
    {
        $this->trickRepository->method('countEntries')->willReturn(12);

        self::assertNull(
            $this->paginationHelper->pagination(
                $this->trickRepository,
                10,
                3
            )
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfPagingIsTooShort()
    {
        $this->trickRepository->method('countEntries')->willReturn(12);

        self::assertNull(
            $this->paginationHelper->pagination(
                $this->trickRepository,
                10,
                0
            )
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnThePageNumber()
    {
        $this->trickRepository->method('countEntries')->willReturn(15);

        self::assertEquals(
            2,
            $this->paginationHelper->pagination(
                $this->trickRepository,
                10,
                1
            )
        );
    }
}
