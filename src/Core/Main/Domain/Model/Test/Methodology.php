<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Test;

use Ramsey\Uuid\Uuid;

class Methodology
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * Methodology constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(?string $id, string $name)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Methodology
     */
    public function setName(string $name): Methodology
    {
        $this->name = $name;
        return $this;
    }
}
