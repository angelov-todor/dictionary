<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

use Core\Main\Domain\Model\Dictionary\Dictionary;
use Core\Main\Application\Service\Word\DoctrineAdapter;
use Doctrine\ORM\EntityManager;

class WordTools
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * WordTools constructor.
     * @param $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return WordTools
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param $words
     * @return string
     */
    public function rhymeform($words)
    {
        $wordsArray = explode(' ', $words);
        $this->toLowerCase($wordsArray);
        $rhymeform = [];

        /* @var $qb \Doctrine\ORM\QueryBuilder */
        foreach ($wordsArray as $word) {
            if (empty($word)) {
                continue;
            }
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('d.word')->from(Dictionary::class, 'd')
                ->where('d.normalized like :word')
                ->setParameter('word', $word);

            $query = $qb->getQuery();
            $found = $query->getOneOrNullResult();

            if ($found) {
                $word = $found['word'];
            }

            $rhymeform[] = Rhymeform::findRhymeform($word);
        }

        $this->toLowerCase($rhymeform);
        return implode(' ', $rhymeform);
    }

    public function reduction($words)
    {
        $wordsArray = explode(' ', $words);
        $this->toLowerCase($wordsArray);
        $done = [];
        $algorithm = new Transcription();
        $adapter = new DoctrineAdapter($this->getEntityManager());
        $adapter->setEntityClassname(Dictionary::class);
        $algorithm->setAdapter($adapter);
        try {
            foreach ($wordsArray as $i => $word) {
                if (empty($word)) {
                    continue;
                }
                $algorithm->setCharacteristic($algorithm->findCharacteristic($word));
                $done[] = $algorithm->reduct($word);
            }
        } catch (\Exception $e) {
            return "n/a";
        }
        return implode(" ", $done);
    }

    public function phonemes($words)
    {
        $wordsArray = explode(' ', $words);
        $this->toLowerCase($wordsArray);
        $done = [];
        $algorithm = new Transcription();
        $adapter = new DoctrineAdapter($this->getEntityManager());
        $adapter->setEntityClassname(Dictionary::class);
        $algorithm->setAdapter($adapter);
        try {
            foreach ($wordsArray as $i => $word) {
                if (empty($word)) {
                    continue;
                }
                $algorithm->setCharacteristic($algorithm->findCharacteristic($word));
                $done[] = $algorithm->toPhonemes($word);
            }
        } catch (\Exception $e) {
            return "n/a";
        }
        return implode(" ", $done);
    }

    public function transcription($words)
    {
        $wordsArray = explode(' ', $words);
        $this->toLowerCase($wordsArray);
        $done = [];
        $algorithm = new Transcription();
        $adapter = new DoctrineAdapter($this->getEntityManager());
        $adapter->setEntityClassname(Dictionary::class);
        $algorithm->setAdapter($adapter);
        try {
            foreach ($wordsArray as $i => $word) {
                if (empty($word)) {
                    continue;
                }
                $algorithm->setCharacteristic($algorithm->findCharacteristic($word));
                $done[] = $algorithm->transcript($word);
            }
        } catch (\Exception $e) {
            return "n/a";
        }
        return implode(" ", $done);
    }

    public function syllables($words)
    {
        $wordsArray = explode(' ', $words);
        $this->toLowerCase($wordsArray);
        $split = [];
        $pt = new Syllables();
        try {
            foreach ($wordsArray as $word) {
                if (empty($word)) {
                    continue;
                }
                $split[] = $pt->processWord($word);
            }
        } catch (\Exception $e) {
            return "n/a";
        }

        return implode(' ', $split);
    }

    protected function toLower($word)
    {
        return mb_strtolower($word, 'UTF-8');
    }

    protected function toLowerCase(array &$array)
    {
        array_walk(
            $array,
            function (&$item) {
                $item = mb_strtolower($item, 'UTF-8');
            }
        );
        return $array;
    }
}
