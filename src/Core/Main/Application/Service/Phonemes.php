<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

/**
 * @author tsys
 */
class Phonemes extends Word
{
    const START = '$';
    const END = "#";
    const VOWEL = 'VO';
    const CONSONANT = 'CO';
    const SOUNDED = 'SO';
    const SOUNDLESS = 'SL';
    const SONOROUS = 'SN';
    //  Краесловно обезвучаване – в края на думата звучните стават беззвучни
    const ENDLOSS = 1;
    //  Вътрешнословна – две различни по звучност:беззвучност съгласни се уподобяват
    //  ставайки двете звучни или беззвучни
    //  Регресивна – следходния асимилира предходния
    const REGRESSION = 2;
    //  Елизия – изпадане на звукове стн, стк, стл, здн, штн
    const ELISSION = 3;
    //  Алофонно редуване – редукция на гласните
    const REDUCTION = 4;

    public static $ruleStrings = [
        self::REDUCTION => "Reduction",
        self::REGRESSION => "Regression",
        self::ELISSION => "Elission",
        self::ENDLOSS => "Endloss"
    ];
    protected $rules = [];

    /**
     * @var array List of all available sounds in the language
     */
    public static $phonemes = [
        "а",
        "ъ",
        "о",
        "у",
        "е",
        "и",
        "б",
        "в",
        "г",
        "д",
        "ж",
        "дж",
        "з",
        "дз",
        "к",
        "л",
        "м",
        "н",
        "п",
        "р",
        "с",
        "т",
        "ф",
        "х",
        "ц",
        "ч",
        "ш",
        "б`",
        "в`",
        "г`",
        "д`",
        "з`",
        "дз`",
        "к`",
        "л`",
        "м`",
        "н`",
        "п`",
        "р`",
        "с`",
        "т`",
        "ф`",
        "х`",
        "ц`",
        "й",
        "йа",
        "йъ"
    ];

    /**
     * @var [] Characteristic types in the database
     */
    protected $types = ['0', '1', '2', '3', '4', 'g', 'g2', 'g3'];

    /**
     * @var mixed Curret word charachteristic
     */
    protected $characteristic = '';

    /**
     * @var [] Гласни "а", "е", "и", "о", "у", "ъ", "я", "ю"
     */
    protected $vowels = [
        "а",
        "е",
        "и",
        "о",
        "у",
        "ъ",
        "я",
        "ю",
        "А",
        "Е",
        "И",
        "О",
        "У",
        "Ъ",
        "Я",
        "Ю"
    ];

    /**
     * @var [] Звучни съгласни "б", "в", "г", "д", "ж", "з"
     */
    protected $soundedConsonants = [
        "б",
        "в",
        "г",
        "д",
        "ж",
        "з",
        "Б",
        "В",
        "Г",
        "Д",
        "Ж",
        "З"
    ];

    /**
     * @var [] Беззвучни съгласни "п", "ф", "к", "т", "ш", "с", "ц", "ч", "х", "щ"
     */
    protected $soundlessConsonants = [
        "п",
        "ф",
        "к",
        "т",
        "ш",
        "с",
        "ц",
        "ч",
        "х",
        "щ",
        "П",
        "Ф",
        "К",
        "Т",
        "Ш",
        "С",
        "Ц",
        "Ч",
        "Х",
        "Щ"
    ];

    /**
     * @var [] Сонорни съгласни "р", "л", "м", "н"
     */
    protected $sonorousConsonants = [
        "р",
        "л",
        "м",
        "н",
        "Р",
        "Л",
        "М",
        "Н"
    ];

    /**
     * @var array Съгласни = Звучни + беззвучни + сонорни + { ь, Ь }
     */
    protected $consonants = [
        "б",
        "в",
        "г",
        "д",
        "ж",
        "з",
        "п",
        "ф",
        "к",
        "т",
        "ш",
        "с",
        "ц",
        "ч",
        "х",
        "ь",
        "р",
        "л",
        "м",
        "н",
        "Б",
        "В",
        "Г",
        "Д",
        "Ж",
        "З",
        "П",
        "Ф",
        "К",
        "Т",
        "Ш",
        "С",
        "Ц",
        "Ч",
        "Х",
        "Ь",
        "Р",
        "Л",
        "М",
        "Н"
    ];

    /**
     * @var array Шумови съгласни = звучни + беззвучни
     */
    protected $noisyConsonants = [
        "б",
        "в",
        "г",
        "д",
        "ж",
        "з",
        "п",
        "ф",
        "к",
        "т",
        "ш",
        "с",
        "ц",
        "ч",
        "х",
        "Б",
        "В",
        "Г",
        "Д",
        "Ж",
        "З",
        "П",
        "Ф",
        "К",
        "Т",
        "Ш",
        "С",
        "Ц",
        "Ч",
        "Х"
    ];

    /**
     * @var array специални "ь", "Ь"
     */
    protected $special = ["ь", "Ь"];

    /**
     * @var array твърди съгласни "б", "п", "в", "ф", "д", "т", "s", "з", "с", \n
     * "г", "к", "ч", "ц", "дж", "дз", "х", "р", "л", "м", "н"
     */
    protected $hardConsonants = [
        "б",
        "п",
        "в",
        "ф",
        "д",
        "т",
        "s",
        "з",
        "с",
        "г",
        "к",
        "ч",
        "ц",
        "дж",
        "дз",
        "х",
        "р",
        "л",
        "м",
        "н"
    ];

    /**
     * Трансформира дума до фонетичното й представяне
     *
     * @param string $word The word to transform
     * @param boolean $asArray How to return transformed word
     * @return string|array The transformed word as string or array
     */
    public function toPhonemes($word, $asArray = false)
    {
        if (!is_string($word)) {
            throw new \InvalidArgumentException(
                sprintf('String expected [%s] given', gettype($word))
            );
        }
        $this->rules = [];
        //  make as array
        $chrArray = $this->toArray($word, true);
        //  add start and end markers
        $startChar = self::START;
        $endChar = self::END;
        array_unshift($chrArray, $startChar);
        array_push($chrArray, $endChar);

        $length = count($chrArray);

        for ($i = $length - 1; $i > 1; $i--) {
            /*
             * Определя дали $next е последната буква в думата
             */
            $isLast = isset($chrArray[$i + 1]) ? ($chrArray[$i + 1] == self::END) : false;
            $next = $chrArray[$i];
            $current = $chrArray[$i - 1];
            $previous = $chrArray[$i - 2];
            ################# Rules #################
            //  краесловно обеззвучаване
            if ($this->isSounded($current) && $this->isSounded($previous) && $next == self::END) {
                $this->rules[self::ENDLOSS] = true;
            }
            // регресивна асимилация
            if ($this->isNoisy($current) && !in_array($current, ['в', 'В']) && $this->isNoisy($previous)) {
                $this->rules[self::REGRESSION] = true;
            }

            #########################################
            //  ако следващата е 'ьо'
            if ($this->isHard($current) && in_array($next, $this->special)) {
                $chrArray[$i - 1] = $current . "`";
                $chrArray[$i] = "";
                continue;
            }
            // ако са от изброените и следващата е 'в'
            //  'т' има по специална обработка
            if (in_array($current, ["п", "с", "ц", "ч", "ш"]) && $next == "в") {
                continue;
            }

            if ($current == "А" || $current == "а") {
                if ($this->isHard($previous) && $next == self::END &&
                    ($this->getCharacteristic() == 'g' || $this->getCharacteristic() == '1')
                ) {
                    $chrArray[$i - 1] = "ъ";
                } else {
                    if ($this->isHard($previous) && $isLast && $next == "т" &&
                        $this->getCharacteristic() == "g"
                    ) {
                        $chrArray[$i - 1] = "ъ";
                    }
                }
            }
            if ($current == "Б" || $current == "б") {
                if ($this->isSoundless($next) || $next == self::END) {
                    $chrArray[$i - 1] = "п";
                }
            }
            if ($current == "В" || $current == "в") {
                if ($this->isSoundless($next) || $next == self::END) {
                    $chrArray[$i - 1] = "ф";
                }
            }
            if ($current == "Г" || $current == "г") {
                if ($this->isSoundless($next) || $next == self::END) {
                    $chrArray[$i - 1] = "к";
                } elseif ($this->isSounded($next) && $isLast) {
                    //  краесловие
                    $chrArray[$i - 1] = "к";
                    $chrArray[$i] = $this->toSoundless($next);
                }
            }
            if ($current == "Д" || $current == "д") {
                if (($previous == "з" && $next == "н") ||
                    ($previous == "ж" && $next == "н") ||
                    ($previous == "л" && $next == "ф") ||
                    ($previous == "л" && $next == "ш")
                ) {
                    $chrArray[$i - 1] = "";
                    $this->rules[self::ELISSION] = true;
                } elseif (($this->isSounded($previous) && $next == self::END) ||
                    ($this->isSounded($previous) && $this->isSounded($next)) ||
                    $next == self::END
                ) {
                    $chrArray[$i - 1] = "т";
                } else {
                    if ($this->isSoundless($next)) {
                        $chrArray[$i - 1] = "т";
                    }
                }
            }
            if ($current == "Ж" || $current == "ж") {
                if ($this->isSoundless($next) || $next == self::END) {
                    $chrArray[$i - 1] = "ш";
                }
            }
            if ($current == "ДЖ" || $current == "дж") {
                if ($next == self::END || $this->isSoundless($next)) {
                    $chrArray[$i - 1] = "ч";
                } elseif ($this->isVowel($next) || $this->isSounded($next) || $this->isSonorous($next)) {
                    $chrArray[$i - 1] = "џ";
                } elseif (in_array($next, ["ь", "о", "я", "а", "ъ"])) {
                    $chrArray[$i - 1] = "s`";
                }
            }
            if ($current == "ДЗ" || $current == "дз") {
                if ($next == self::END || $this->isSoundless($next)) {
                    $chrArray[$i - 1] = "ц";
                } elseif ($this->isVowel($next) || $this->isSounded($next) || $this->isSonorous($next)) {
                    $chrArray[$i - 1] = "s";
                } elseif (in_array($next, ["ь", "ю", "у", "а", "я", "ъ"])) {
                    $chrArray[$i - 1] = "ц`";
                }
            }
            if ($current == "З" || $current == "з") {
                if ($this->isSoundless($next) || $next == self::END) {
                    $chrArray[$i - 1] = "с";
                }
            }
            if ($current == "К" || $current == "к") {
                if ($this->isSounded($next) && $next != "в") {
                    $chrArray[$i - 1] = "г";
                }
            }
            if ($current == "П" || $current == "п") {
                if ($this->isSounded($next)) {
                    $chrArray[$i - 1] = "б";
                }
            }
            if ($current == "С" || $current == "с") {
                if ($this->isSounded($next) && !in_array($next, ['в'])) {
                    $chrArray[$i - 1] = "з";
                }
            }
            if ($current == "Т" || $current == "т") {
                if ($next == self::END && ($previous == "с" || $previous == "С" || $previous
                        == "ш" || $previous == "Ш")
                ) {
                    $chrArray[$i - 1] = "";
                    //  mark the rule
                    $this->rules[self::ELISSION] = true;
                } else {
                    if (($previous == "С" || $previous == "с") &&
                        in_array($next, ["н", "ц", "м", "в", "ч", "к", "л"])
                    ) {
                        $chrArray[$i - 1] = "";
                        //  mark the rule
                        $this->rules[self::ELISSION] = true;
                    } else {
                        if ($this->isSounded($next) && $next != "в") {
                            $chrArray[$i - 1] = "д";
                        }
                    }
                }
            }
            if ($current == "Ф" || $current == "ф") {
                if ($this->isSounded($next)) {
                    $chrArray[$i - 1] = "в";
                }
            }
            if ($current == "Ч" || $current == "ч") {
                if ($this->isSounded($next)) {
                    $chrArray[$i - 1] = "џ";
                }
            }
            if ($current == "Ц" || $current == "ц") {
                if ($this->isSounded($next)) {
                    $chrArray[$i - 1] = "s";
                }
            }
            if ($current == "Ш" || $current == "ш") {
                if ($this->isSounded($next)) {
                    $chrArray[$i - 1] = "ж";
                }
            }
            if ($current == "Щ" || $current == "щ") {
                if (in_array($next, [self::END, "к", "н"])) {
                    $chrArray[$i - 1] = "ш";
                } else {
                    $chrArray[$i - 1] = "шт";
                }
            }
            if ($current == "Ю" || $current == "ю") {
                if ($previous == self::START || $this->isVowel($previous)) {
                    $chrArray[$i - 1] = 'йу';
                } elseif ($this->isSoft($previous, $current) || $previous == 'й') {
                    $chrArray[$i - 1] = '`у';
                }
            }
            /**
             * я в началото на думата или след гласна с х-ка 0 става йа
             * я след съгласна с х-ка 0 става 'а
             * я след гласна с х-ка g1 или g2 и може би и други става йъ
             * я след съгласна с х-ка g1 или g2 и може би други става 'ъ
             */
            if ($current == "Я" || $current == "я") {
                if ($this->isVowel($previous) &&
                    $next == self::END &&
                    in_array($this->getCharacteristic(), ['3', 'g3'])
                ) {
                    $chrArray[$i - 1] = "йъ";
                    continue;
                }
                if ($this->isVowel($previous) && in_array($this->getCharacteristic(), ['g'])
                    &&
                    ($next == self::END || $isLast)
                ) {
                    $chrArray[$i - 1] = "йъ";
                    continue;
                }
                if ($this->isVowel($previous) && in_array($this->getCharacteristic(), ['g3'])) {
                    $chrArray[$i - 1] = "йъ";
                    continue;
                }
                if ($this->isVowel($previous) && in_array($this->getCharacteristic(), ['3'])) {
                    $chrArray[$i - 1] = "йъ";
                    continue;
                }
                if ($previous == self::START ||
                    ($this->isVowel($previous) &&
                        in_array($this->getCharacteristic(), ['0', '3', 'g3'])
                    )
                ) {
                    $chrArray[$i - 1] = 'йа';
                    continue;
                }
                if ($this->isConsonant($previous) && $this->getCharacteristic() == '0') {
                    $chrArray[$i - 1] = '`а';
                    continue;
                }
                if ($this->isVowel($previous) &&
                    in_array($this->getCharacteristic(), ['g1', 'g2'])
                ) {
                    $chrArray[$i - 1] = "йъ";
                    continue;
                }
                if ($this->isConsonant($previous) &&
                    in_array($this->getCharacteristic(), ['g', 'g1', 'g2', '2'])
                ) {
                    $chrArray[$i - 1] = '`ъ';
                    continue;
                }
            }
        }

        //  remove end symbol
        array_pop($chrArray);
        //  remove start symbol
        array_shift($chrArray);
        if ($asArray) {
            return $chrArray;
        }
        //  return the word
        return implode("", $chrArray);
    }

    /**
     * Трансформира до беззвучна съгласна
     *
     * @param string $char
     * @return string
     */
    protected function toSoundless($char)
    {
        $key = array_search($char, $this->soundedConsonants);
        return $this->soundlessConsonants[$key];
    }

    /**
     * Определя дали съгласната е мека
     * Мека съгласна – съгласна  следвана от 'ю','я','ь','о';
     * @param string $char
     * @param string $next
     * @return boolean
     */
    protected function isSoft($char, $next)
    {
        if (!$this->isConsonant($char)) {
            return false;
        }
        if (!in_array(
            $char,
            [
                'б',
                'в',
                'г',
                'д',
                'з',
                'дз',
                'к',
                'л',
                'м',
                'н',
                'п',
                'р',
                'с',
                'т',
                'ф',
                'х',
                'ц',
                'й'
            ]
        )) {
            return false;
        }
        if (in_array($next, ["ю", "я", "ь", "о"])) {
            return true;
        }
        return false;
    }

    /**
     * Определя дали е твърда съгласна
     *
     * @param string $char
     * @return boolean
     */
    protected function isHard($char)
    {
        return in_array($char, $this->hardConsonants);
    }

    /**
     * Определя дали е съгласна
     *
     * @param string $char
     * @return boolean
     */
    protected function isConsonant($char)
    {
        if (in_array($char, $this->consonants)) {
            return true;
        }
        return false;
    }

    /**
     * Определя дали е беззвучна
     *
     * @param string $char
     * @return boolean
     */
    protected function isSoundless($char)
    {
        if (in_array($char, $this->soundlessConsonants)) {
            return true;
        }
        return false;
    }

    /**
     * Определя дали символа е шумова съгласна
     *
     * @param string $char
     * @return boolean
     */
    protected function isSounded($char)
    {
        if (in_array($char, $this->soundedConsonants)) {
            return true;
        }
        return false;
    }

    /**
     * Определя дали дадения символ е гласна
     *
     * @param string $char
     * @return boolean
     */
    protected function isVowel($char)
    {
        if (in_array($char, $this->vowels)) {
            return true;
        }
        return false;
    }

    /**
     * Get current word characteristic
     *
     * @return mixed
     */
    protected function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set word characteristic to use
     *
     * @param string $characteristic
     * @return Phonemes
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;
        return $this;
    }

    /**
     * Определя дали е сонорна
     *
     * @param string $char
     * @return boolean
     */
    protected function isSonorous($char)
    {
        if (in_array($char, $this->sonorousConsonants)) {
            return true;
        }
        return false;
    }

    /**
     * Определя дали дадения стринг е енклитика
     *
     * @param string $word
     * @return boolean
     */
    protected function isEnclitic($word)
    {
        $enclitix = [
            "ви",
            "го",
            "и",
            "им",
            "ле",
            "ли",
            "ма",
            "ме",
            "ми",
            "му",
            "ни",
            "са",
            "се",
            "си",
            "сте",
            "съм",
            "те",
            "ти",
            "ще",
            "щем",
            "я",
        ];

        if (in_array($word, $enclitix)) {
            return true;
        }
        return false;
    }

    /**
     * Check if character is silent (Безшумен)
     *
     * @param string $char
     * @return boolean
     */
    protected function isSilent($char)
    {
        if ($this->isConsonant($char)) {
            return !in_array($char, $this->noisyConsonants);
        }
        return false;
    }

    /**
     * Check if character is noisy. (Шумова)
     *
     * @param string $char
     * @return boolean
     */
    protected function isNoisy($char)
    {
        return in_array($char, $this->noisyConsonants);
    }

    /**
     * Get the rules used to transform the word
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
