<?php

namespace App\Application\Helpers;

use App\Application\Helpers\Interfaces\SluggerHelperInterface;

class SluggerHelper implements SluggerHelperInterface
{
    /**
     * @var string
     */
    private $spaceCharacter;

    /**
     * SluggerHelper constructor.
     * @param null|string $spaceCharacter
     */
    public function __construct(?string $spaceCharacter = '-')
    {
        $this->spaceCharacter = $spaceCharacter;
    }

    /**
     * @return string
     */
    public function getSpaceCharacter(): string
    {
        return $this->spaceCharacter;
    }

    /**
     * @param string $spaceCharacter
     */
    public function setSpaceCharacter(string $spaceCharacter): void
    {
        $this->spaceCharacter = $spaceCharacter;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function slugify(string $text): string
    {
        $text = strtolower($text);

        $text = $this->removeAccents($text);
        $text = $this->removeSpecialCharacters($text);

        $text = preg_replace('/[[:punct:]]+/', '', $text);
        $text = preg_replace('/[^\w]+/', $this->spaceCharacter, $text);

        return trim(strip_tags($text), '-');
    }

    private function removeAccents(string $text): string
    {
        $text = str_replace(['é', 'è', 'ê', 'ë'], 'e', $text);
        $text = str_replace(['à', 'â'], 'a', $text);
        $text = str_replace('ô', 'o', $text);
        $text = str_replace(['ù', 'û', 'ü'], 'u', $text);

        return $text;
    }

    private function removeSpecialCharacters(string $text): string
    {
        $text = str_replace('ç', 'c', $text);
        $text = str_replace('æ', 'ae', $text);
        $text = str_replace('œ', 'oe', $text);

        return $text;
    }
}
