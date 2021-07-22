<?php

namespace GeorgRinger\News\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class ClassParser
{
    private $classes = [];
    private $extends = [];
    private $implements = [];

    const STATE_CLASS_HEAD = 100001;
    const STATE_FUNCTION_HEAD = 100002;

    public function getClasses()
    {
        return $this->classes;
    }

    public function getFirstClass()
    {
        return array_shift($this->classes);
    }

    public function getClassesImplementing($interface): array
    {
        $implementers = [];
        if (isset($this->implements[$interface])) {
            foreach ($this->implements[$interface] as $name) {
                $implementers[$name] = $this->classes[$name];
            }
        }
        return $implementers;
    }

    public function getClassesExtending($class): array
    {
        $extenders = [];
        if (isset($this->extends[$class])) {
            foreach ($this->extends[$class] as $name) {
                $extenders[$name] = $this->classes[$name];
            }
        }
        return $extenders;
    }

    public function parse($file): void
    {
        $file = realpath($file);
        $tokens = token_get_all(file_get_contents($file));
        $classes = [];
        $clsc = 0;

        $si = null;
        $depth = 0;
        $mod = [];
        $doc = null;
        $state = null;
        $inFunction = false;
        $functionName = '';
        $lastLine = 0;
        foreach ($tokens as $idx => &$token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_DOC_COMMENT:
                        $doc = $token[1];
                        break;
                    case T_PUBLIC:
                    case T_PRIVATE:
                    case T_ABSTRACT:
                    case T_PROTECTED:
                        $mod[] = $token[1];
                        break;
                    case T_CLASS:
                    case T_FUNCTION:
                        $state = $token[0];
                        break;
                    case T_EXTENDS:
                    case T_IMPLEMENTS:
                        switch ($state) {
                            case self::STATE_CLASS_HEAD:
                            case T_EXTENDS:
                                $state = $token[0];
                                break;
                        }
                        break;
                    case T_CLOSE_TAG:
                        $classes[$depth]['eol'] = $token[2];
                        break;
                    case T_STRING:
                        switch ($state) {
                            case T_CLASS:
                                $state = self::STATE_CLASS_HEAD;
                                $si = $token[1];
                                $classes[] = [
                                    'name' => $token[1],
                                    'modifiers' => $mod,
                                    'doc' => $doc,
                                    'start' => $token[2]
                                ];
                                break;
                            case T_FUNCTION:
                                $state = self::STATE_FUNCTION_HEAD;
                                $clsc = count($classes);
                                if ($depth > 0 && $clsc) {
                                    $inFunction = true;
                                    $functionName = $token[1];
                                    $classes[$clsc - 1]['functions'][$token[1]] = [
                                        'modifiers' => $mod,
                                        'doc' => $doc,
                                        'start' => $token[2]
                                    ];
                                }
                                break;
                            case T_IMPLEMENTS:
                            case T_EXTENDS:
                                $clsc = count($classes);
                                $classes[$clsc - 1][$state == T_IMPLEMENTS ? 'implements' : 'extends'][] = $token[1];
                                break;
                        }
                        break;
                }
                $lastLine = $token[2];
            } else {
                switch ($token) {
                    case '{':
                        $depth++;
                        break;
                    case '}':
                        $depth--;
                        break;
                }

                if ($token === '}') {
                    if ($inFunction) {
                        $classes[$clsc - 1]['functions'][$functionName]['end'] = $lastLine;
                        $inFunction = false;
                    }
                }

                switch ($token) {
                    case '{':
                    case '}':
                    case ';':
                        $state = 0;
                        $doc = null;
                        $mod = [];
                        break;
                }
            }
        }

        foreach ($classes as $class) {
            $class['file'] = $file;
            $this->classes[$class['name']] = $class;

            if (!empty($class['implements'])) {
                foreach ($class['implements'] as $name) {
                    $this->implements[$name][] = $class['name'];
                }
            }

            if (!empty($class['extends'])) {
                foreach ($class['extends'] as $name) {
                    $this->extends[$name][] = $class['name'];
                }
            }
        }
    }
}
