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
        $fp = fopen($file, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) {
                break;
            }
            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);
            if (strpos($buffer, '{') === false) {
                continue;
            }
            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\' . $tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }

        fclose($fp);

        return $namespace . '\\' . $class;
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