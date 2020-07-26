<?php

declare(strict_types = 1);

namespace LanguageDetection;

use LanguageDetection\Tokenizer\WhitespaceTokenizer;


/**
 * Class Language
 *
 * @copyright 2016-2018 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class Language extends NgramParser
{
    const RESOURCE_JSON = __DIR__ .  '/../../resources';

    private $resourceDir;

    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * Loads all language files
     *
     * @param array $lang List of ISO 639-1 codes, that should be used in the detection phase
     * @param string $dirname Name of the directory where the translations files are located
     */
    public function __construct(array $lang = [], string $dirname = '')
    {
        if (empty($dirname)) {
            $dirname = self::RESOURCE_JSON;
        } else if (!is_dir($dirname) || !is_readable($dirname)) {
            throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
        } else {
            $dirname = rtrim($dirname, '/');
        }

        
        $this->resourceDir = $dirname;
        $dirname           .= '/*/*.json';
        
        $isEmpty = empty($lang);

        foreach (glob($dirname) as $json)
        {
            $fileName = basename($json, '.json');
            if ($fileName == 'props') {
                continue;
            }
            
            if ($isEmpty || in_array($fileName, $lang))
            {
                $this->tokens += json_decode(file_get_contents($json), true);
            }
        }
    }

    /**
     * Get corpus directory
     * 
     * @return string absolute path of resource directory
     */
    protected function getResourceDir(): string
    {
        return $this->resourceDir;
    }

    /**
     * Detects the language from a given text string
     *
     * @param string $str
     * @return LanguageResult
     */
    public function detect(string $str): LanguageResultInterface
    {
        $str = mb_strtolower($str);

        $samples = $this->getNgrams($str);

        $result = [];

        if (count($samples) > 0)
        {
            foreach ($this->tokens as $lang => $value)
            {
                $index = $sum = 0;
                $value = array_flip($value);

                foreach ($samples as $v)
                {
                    if (isset($value[$v]))
                    {
                        $x = $index++ - $value[$v];
                        $y = $x >> (PHP_INT_SIZE * 8);
                        $sum += ($x + $y) ^ $y;
                        continue;
                    }

                    $sum += $this->maxNgrams;
                    ++$index;
                }

                $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
            }

            arsort($result, SORT_NUMERIC);
        }

        return new LanguageResult($result);
    }

    public function getSupportLanguages(): array
    {
        $result = array_map(function($value) {
            return basename($value, '.json');
        }, array_filter(glob($this->getResourceDir() . '/*/*.json'), 'is_file'));

        return $result;
    }

    public function getLanguageProps($lang = null)
    {
        $file = $this->getResourceDir() . "/$lang/props.json";
        if (is_file($file) && is_readable($file)) {
            return json_decode(file_get_contents($file), true);
        }

        return [];
    }
}
