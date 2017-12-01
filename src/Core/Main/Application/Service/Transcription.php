<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

class Transcription extends Reduction
{
    protected $transcriptionMap = [
        'е' => [
            [
                'next' => ['м', 'н'],
                'replace' => 'ẽ'
            ]
        ],
        'о' => [
            [
                'next' => ['м', 'н'],
                'replace' => 'õ'
            ]
        ],
        'б' => [
            [
                'next' => ['о', 'у'],
                'replace' => 'б°'
            ]
        ],
        'в' => [
            [
                'next' => ['о', 'у'],
                'replace' => 'в°'
            ]
        ],
        'г' => [
            [
                'next' => ['е', 'и'],
                'replace' => 'г¬'
            ]
        ],
        'д' => [
            [
                'next' => ['л'],
                'replace' => 'd'
            ]
        ],
        'т' => [
            [
                'next' => ['л'],
                'replace' => 't'
            ]
        ],
        'к' => [
            [
                'next' => ['е', 'и'],
                'replace' => 'к`'
            ]
        ],
        'л' => [
            [
                'next' => ['е', 'и'],
                'replace' => 'l'
            ]
        ],
        'м' => [
            [
                'next' => ['о', 'у'],
                'replace' => 'м°'
            ]
        ],
        'н' => [
            [
                'next' => ['с', 'з', 'ш', 'й', 'ж', 'х', 'в', 'с`', 'з`', 'х`', 'в`'],
                'replace' => 'v'
            ],
            [
                'next' => ['к', 'г', 'х'],
                'replace' => 'η'
            ]
        ],
        'п' => [
            [
                'next' => ['о', 'у'],
                'replace' => 'п°'
            ]
        ],
        'ф' => [
            [
                'next' => ['о', 'у'],
                'replace' => 'ф°'
            ]
        ],
        'х' => [
            [
                'next' => ['е', 'и'],
                'replace' => 'h'
            ],
            [
                'next' => ['б', 'в', 'г', 'д', 'ж', 'з'],
                'replace' => 'γ'
            ]
        ],
    ];

    /**
     * Transcription algorithm
     *
     * @param string $word The word to process
     * @param boolean $asArray How to return the processed word
     * @return string|array
     */
    public function transcript($word, $asArray = false)
    {
        $wordObj = $this->findWord($word);
        if (!$wordObj) {
            return false;
        }
        $this->setCharacteristic($wordObj['characteristic']);
        //  simply run the parent algorithm
        $transformed = $this->reduct($wordObj['normalized'], true);

        $length = count($transformed);
        for ($i = 0; $i < $length - 1; $i++) {
            $current = $transformed[$i];
            $next = $transformed[$i + 1];
            if (!array_key_exists($current, $this->transcriptionMap)) {
                continue;
            }
            foreach ($this->transcriptionMap[$current] as $map) {
                if (in_array($next, $map['next'])) {
                    $transformed[$i] = $map['replace'];
                    break;
                }
            }
        }

        if ($asArray) {
            return $transformed;
        }
        return implode("", $transformed);
    }
}
