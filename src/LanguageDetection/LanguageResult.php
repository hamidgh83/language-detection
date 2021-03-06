<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class LanguageResult
 *
 * @copyright 2016-2018 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class LanguageResult implements \JsonSerializable, \IteratorAggregate, \ArrayAccess, LanguageResultInterface
{
    const THRESHOLD = .025;

    /**
     * @var array
     */
    protected $result = [];

    /**
     * LanguageResult constructor.
     * @param array $result
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->result[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->result[] = $value;
        } else {
            $this->result[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) key($this->result);
    }

    /**
     * @param \string[] ...$whitelist
     * @return LanguageResultInterface
     */
    public function whitelist(string ...$whitelist): LanguageResultInterface
    {
        return new LanguageResult(array_intersect_key($this->result, array_flip($whitelist)));
    }

    /**
     * @param \string[] ...$blacklist
     * @return LanguageResultInterface
     */
    public function blacklist(string ...$blacklist): LanguageResultInterface
    {
        return new LanguageResult(array_diff_key($this->result, array_flip($blacklist)));
    }

    /**
     * @return array
     */
    public function close(): array
    {
        return $this->result;
    }

    /**
     * @return LanguageResultInterface
     */
    public function bestResults(): LanguageResultInterface
    {
        if (!count($this->result))
        {
            return new LanguageResult;
        }

        $first = array_values($this->result)[0];

        return new LanguageResult(array_filter($this->result, function ($value) use ($first) {
            return ($first - $value) <= self::THRESHOLD ? true : false;
        }));
    }

    /**
     * @return String
     */
    public function bestResult(): String
    {
        $results = $this->bestResults()->close() ?? null;
        
        if (is_array($results) && count($results) > 0) {
            return array_search(max($results), $results);
        }

        return '';
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->result);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return LanguageResultInterface
     */
    public function limit(int $offset, int $length = null): LanguageResultInterface
    {
        return new LanguageResult(array_slice($this->result, $offset, $length));
    }
}
