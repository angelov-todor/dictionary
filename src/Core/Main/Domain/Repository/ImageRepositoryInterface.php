<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Image;

interface ImageRepositoryInterface
{
    /**
     * @return array
     */
    public function view();

    /**
     * @param Image $image
     * @return Image
     */
    public function add(Image $image): Image;

    /**
     * @param Image $image
     */
    public function remove(Image $image): void;

    /**
     * @return Image
     */
    public function getRandomImage(): Image;
}
