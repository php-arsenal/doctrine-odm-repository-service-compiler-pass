<?php

namespace PhpArsenal\DoctrineODMRepositoryServiceCompilerPass;

use Symfony\Component\Finder\Finder;

class FullyQualifiedClassNameReader
{
    public static function readAll(string $sourceDir): array
    {
        $classNames = [];

        foreach (static::getFilePaths($sourceDir) as $filePath) {
            $fileContents = file_get_contents($filePath);
            $classNames[] = static::getFileNamespace($fileContents) . '\\' . static::getFileClassName($fileContents);
        }

        return $classNames;
    }

    private static function getFileNamespace(string $fileContents): string
    {
        preg_match('/namespace\s+([^;]+)/m', $fileContents, $match);

        return $match[1];
    }

    private static function getFileClassName(string $fileContents): string
    {
        preg_match('/(class|trait|interface)\s+([^\s]+)/m', $fileContents, $match);

        return $match[2];
    }

    private static function getFilePaths(string $sourceDir): array
    {
        $filePaths = [];

        foreach (Finder::create()->files()->in($sourceDir)->name('*.php') as $file) {
            $filePaths[] = $file->getRealpath();
        }

        return $filePaths;
    }
}
