<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Word;

use Doctrine\ORM\EntityManager;

class DoctrineAdapter implements AdapterInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClassname;

    /**
     * DoctrineAdapter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $entityClassname
     * @return DoctrineAdapter
     */
    public function setEntityClassname(string $entityClassname): DoctrineAdapter
    {
        $this->entityClassname = $entityClassname;
        return $this;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return DoctrineAdapter
     */
    public function setEntityManager(EntityManager $entityManager): DoctrineAdapter
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param string $word
     * @return mixed
     */
    public function findWord($word)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('d')
            ->from($this->entityClassname, 'd')
            ->where('d.normalized like :word')
            ->setParameter('word', $word);

        $query = $qb->getQuery();
        $found = $query->getOneOrNullResult();

        return json_decode(json_encode($found), true);
    }
}
