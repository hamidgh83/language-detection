<?php

namespace LanguageDetection;

interface LanguageResultInterface
{
    public function whitelist(string ...$whiteklist): LanguageResultInterface;
    
    public function blacklist(string ...$blacklist): LanguageResultInterface;

    public function bestResult(): String;

    public function bestResults(): LanguageResultInterface;

    public function limit(int $offset, int $length = null): LanguageResultInterface;
    
    public function close(): array;
}