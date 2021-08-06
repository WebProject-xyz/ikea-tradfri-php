<?php
declare(strict_types=1);

$header = <<<'EOF'
HEADER
EOF;

$finder = \PhpCsFixer\Finder::create()
    ->files()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->append([__FILE__])
    ->name('*.php');

$rules = [
    // start symfony set
    '@Symfony'                    => true,
    'binary_operator_spaces'      => [
        'default'   => 'align',
        'operators' => [
            '??' => 'single_space',
        ],
    ],
    'concat_space'                            => ['spacing' => 'one'],
    '@PhpCsFixer:risky'                       => true,
    'encoding'                                => true,
    'single_blank_line_before_namespace'      => true,
    'blank_line_after_opening_tag'            => false,
    'strict_param'                            => true,
    'no_useless_else'                         => true,
    'no_useless_return'                       => true,
    'modernize_types_casting'                 => true,
    'declare_strict_types'                    => true,
    'dir_constant'                            => true,
    'no_whitespace_in_blank_line'             => true,

    '@Symfony:risky'                         => true,
    '@PHPUnit75Migration:risky'              => true,
    'php_unit_dedicate_assert'               => ['target' => 'newest'],
    'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
    'fopen_flags'                            => false,
    'combine_nested_dirname'                 => true,

    'ordered_imports'           => [
        'sort_algorithm' => 'alpha',
        'imports_order'  => [
            'const', 'class', 'function',
        ],
    ],

    'global_namespace_import'   => [
        'import_classes'   => true,
        'import_functions' => true,
        'import_constants' => true,
    ],
];

$config = new \PhpCsFixer\Config('default');
$config->setRules($rules);
$config->setRiskyAllowed(true);
$config->setUsingCache(true);
$config->setLineEnding("\n");
$config->setFinder($finder);

return $config;
