<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\DataTransformer;

use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\Annotation as Serializer;

class PaginatedCollection extends PaginatedRepresentation
{
    /**
     * @var int
     *
     * @Serializer\Expose
     * @Serializer\Type("integer")
     * @Serializer\XmlAttribute
     */
    protected $count;

    /**
     * PaginatedCollection constructor.
     * @param $inline
     * @param $route
     * @param array $parameters
     * @param bool $page
     * @param null $limit
     * @param null $pages
     * @param null $pageParameterName
     * @param null $limitParameterName
     * @param bool $absolute
     * @param null $total
     * @param int $count
     */
    public function __construct(
        $inline,
        $route,
        array $parameters,
        $page,
        $limit,
        $pages,
        $pageParameterName = null,
        $limitParameterName = null,
        $absolute = false,
        $total = null,
        $count = 0
    ) {
        parent::__construct(
            $inline,
            $route,
            $parameters,
            $page,
            $limit,
            $pages,
            $pageParameterName,
            $limitParameterName,
            $absolute,
            $total
        );

        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
