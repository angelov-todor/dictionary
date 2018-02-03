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
     * @param string $criteria
     * @return Image
     */
    public function getImageByCriteria(string $criteria): Image;

    /**
     * @param int $page
     * @param int $limit
     * @param null|string $term
     * @return array
     */
    public function viewBy(int $page, int $limit, ?string $term): array;

    /**
     * @param null|string $term
     * @return int
     */
    public function countBy(?string $term): int;

    /**
     * @param int $id
     * @return Image|null
     */
    public function ofId(int $id): ?Image;
}
