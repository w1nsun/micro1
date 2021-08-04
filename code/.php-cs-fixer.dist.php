<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor', 'config', 'bin', 'public'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unreachable_default_argument_value' => false,
        'braces' => ['allow_single_line_closure' => true],
        'heredoc_to_nowdoc' => false,
        'phpdoc_annotation_without_dot' => false,
        'void_return' => true,
        'return_type_declaration' => true,
        'declare_strict_types' => true,
        'cast_spaces' => ['space' => 'single'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
