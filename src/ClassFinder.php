<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2019 StageM Team
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_ClassFinder
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Stagem\ClassFinder;

use Symfony\Component\Finder\Finder;

class ClassFinder
{
    public function getClassFromFile($file)
    {
        $content = file_get_contents($file);
        $tokens = token_get_all($content);
        $fqcns = '';
        $namespace = '';
        for ($index = 0; isset($tokens[$index]); $index++) {
            if (!isset($tokens[$index][0])) {
                continue;
            }
            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2; // Skip namespace keyword and whitespace
                while (isset($tokens[$index]) && is_array($tokens[$index])) {
                    $namespace .= $tokens[$index++][1];
                }
            }
            if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                $index += 2; // Skip class keyword and whitespace
                $fqcns = $namespace.'\\'.$tokens[$index][1];

                # break if you have one class per file (psr-4 compliant)
                # otherwise you'll need to handle class constants (Foo::class)
                break;
            }
        }

        return $fqcns;
    }

    public function getClassesInDir($dir)
    {
        $finder = new Finder();
        $finder->files()->name('*.php')->in($dir);

        if (!$finder->hasResults()) {
            return false;
        }


        $classes = [];
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $classes[] = $this->getClassFromFile($absoluteFilePath);
        }

        return $classes;
    }
}