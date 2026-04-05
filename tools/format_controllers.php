<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($iterator as $fileInfo) {
    if (!$fileInfo->isFile() || strtolower($fileInfo->getExtension()) !== 'php') {
        continue;
    }

    $path = $fileInfo->getPathname();
    $contents = file_get_contents($path);
    $lines = preg_split('/\R/', $contents);

    $curlyDepth = 0;
    $squareDepth = 0;
    $inBlockComment = false;
    $formatted = [];

    foreach ($lines as $line) {
        $trimmed = ltrim($line);

        if ($trimmed === '') {
            $formatted[] = '';
            continue;
        }

        $indentCurly = $curlyDepth;
        $indentSquare = $squareDepth;
        $leading = $trimmed;

        while ($leading !== '' && ($leading[0] === '}' || $leading[0] === ']')) {
            if ($leading[0] === '}') {
                $indentCurly = max(0, $indentCurly - 1);
            } else {
                $indentSquare = max(0, $indentSquare - 1);
            }

            $leading = ltrim(substr($leading, 1));
        }

        $extraContinuation = 0;
        if (
            str_starts_with($trimmed, '.')
            || str_starts_with($trimmed, '->')
            || str_starts_with($trimmed, '?->')
        ) {
            $extraContinuation = 1;
        }

        $formatted[] = str_repeat('    ', max(0, $indentCurly + $indentSquare + $extraContinuation)) . $trimmed;

        $inSingle = false;
        $inDouble = false;
        $len = strlen($line);

        for ($i = 0; $i < $len; $i++) {
            $ch = $line[$i];
            $next = $i + 1 < $len ? $line[$i + 1] : '';

            if ($inBlockComment) {
                if ($ch === '*' && $next === '/') {
                    $inBlockComment = false;
                    $i++;
                }

                continue;
            }

            if ($inSingle) {
                if ($ch === '\\') {
                    $i++;
                    continue;
                }

                if ($ch === "'") {
                    $inSingle = false;
                }

                continue;
            }

            if ($inDouble) {
                if ($ch === '\\') {
                    $i++;
                    continue;
                }

                if ($ch === '"') {
                    $inDouble = false;
                }

                continue;
            }

            if ($ch === '/' && $next === '/') {
                break;
            }

            if ($ch === '#') {
                break;
            }

            if ($ch === '/' && $next === '*') {
                $inBlockComment = true;
                $i++;
                continue;
            }

            if ($ch === "'") {
                $inSingle = true;
                continue;
            }

            if ($ch === '"') {
                $inDouble = true;
                continue;
            }

            if ($ch === '{') {
                $curlyDepth++;
                continue;
            }

            if ($ch === '}') {
                $curlyDepth = max(0, $curlyDepth - 1);
                continue;
            }

            if ($ch === '[') {
                $squareDepth++;
                continue;
            }

            if ($ch === ']') {
                $squareDepth = max(0, $squareDepth - 1);
            }
        }
    }

    $newContents = implode(PHP_EOL, $formatted);

    if ($contents !== $newContents) {
        file_put_contents($path, $newContents);
    }
}
