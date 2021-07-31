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
    // '@Symfony' => true,
    '@PSR12'                      => true,
    'array_syntax'                => ['syntax' => 'short'],
    'backtick_to_shell_exec'      => true,
    'binary_operator_spaces'      => [
        'default'   => 'align',
        'operators' => [
            '??' => 'single_space',
        ],
    ],
    'blank_line_before_statement' => ['statements' => ['return']],
    'braces'                      => [
        'allow_single_line_anonymous_class_with_empty_body' => true,
        'allow_single_line_closure'                         => true,
    ],
    'cast_spaces'                             => true,
    'class_attributes_separation'             => ['elements' => ['method' => 'one']],
    'class_definition'                        => ['single_line' => true],
    'clean_namespace'                         => true,
    'concat_space'                            => ['spacing' => 'one'],
    'echo_tag_syntax'                         => true,
    'fully_qualified_strict_types'            => true,
    'function_typehint_space'                 => true,
    'general_phpdoc_tag_rename'               => ['replacements' => ['inheritDocs' => 'inheritDoc']],
    'include'                                 => true,
    'increment_style'                         => true,
    'lambda_not_used_import'                  => true,
    'linebreak_after_opening_tag'             => true,
    'magic_constant_casing'                   => true,
    'magic_method_casing'                     => true,
    'method_argument_space'                   => true,
    'native_function_casing'                  => true,
    'native_function_type_declaration_casing' => true,
    'no_alias_language_construct_call'        => true,
    'no_alternative_syntax'                   => true,
    'no_binary_string'                        => true,
    'no_blank_lines_after_phpdoc'             => true,
    'no_empty_comment'                        => true,
    'no_empty_phpdoc'                         => true,
    'no_empty_statement'                      => true,
    'no_extra_blank_lines'                    => [
        'tokens' => [
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'square_brace_block',
            'switch',
            'throw',
            'use',
            'use_trait',
        ],
    ],
    'no_leading_namespace_whitespace'             => true,
    'no_mixed_echo_print'                         => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_short_bool_cast'                          => true,
    'no_singleline_whitespace_before_semicolons'  => true,
    'no_spaces_around_offset'                     => true,
    'no_superfluous_phpdoc_tags'                  => ['allow_mixed' => true, 'allow_unused_params' => true],
    'no_trailing_comma_in_list_call'              => true,
    'no_trailing_comma_in_singleline_array'       => true,
    'no_unneeded_control_parentheses'             => [
        'statements' => [
            'break',
            'clone',
            'continue',
            'echo_print',
            'return',
            'switch_case',
            'yield',
            'yield_from',
        ],
    ],
    'no_unneeded_curly_braces'            => ['namespaces' => true],
    'no_unset_cast'                       => true,
    'no_unused_imports'                   => true,
    'no_whitespace_before_comma_in_array' => true,
    'normalize_index_brace'               => true,
    'object_operator_without_whitespace'  => true,
    // 'ordered_imports' => true,
    'php_unit_fqcn_annotation'                      => true,
    'php_unit_method_casing'                        => true,
    'phpdoc_align'                                  => ['tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var']],
    'phpdoc_annotation_without_dot'                 => true,
    'phpdoc_indent'                                 => true,
    'phpdoc_inline_tag_normalizer'                  => true,
    'phpdoc_no_access'                              => true,
    'phpdoc_no_alias_tag'                           => true,
    'phpdoc_no_package'                             => true,
    'phpdoc_no_useless_inheritdoc'                  => true,
    'phpdoc_return_self_reference'                  => true,
    'phpdoc_scalar'                                 => true,
    'phpdoc_separation'                             => true,
    'phpdoc_single_line_var_spacing'                => true,
    'phpdoc_summary'                                => true,
    'phpdoc_tag_type'                               => ['tags' => ['inheritDoc' => 'inline']],
    'phpdoc_to_comment'                             => false, // oeg edit of symfony ruleSet because phpstan
    'phpdoc_trim'                                   => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'phpdoc_types'                                  => true,
    'phpdoc_types_order'                            => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
    'phpdoc_var_without_name'                       => true,
    'protected_to_private'                          => true,
    'semicolon_after_instruction'                   => true,
    'single_class_element_per_statement'            => true,
    'single_line_comment_style'                     => ['comment_types' => ['hash']],
    'single_line_throw'                             => true,
    'single_quote'                                  => true,
    'single_space_after_construct'                  => true,
    'space_after_semicolon'                         => ['remove_in_empty_for_expressions' => true],
    'standardize_increment'                         => true,
    'standardize_not_equals'                        => true,
    'switch_continue_to_break'                      => true,
    'trailing_comma_in_multiline'                   => true,
    'trim_array_spaces'                             => true,
    'unary_operator_spaces'                         => true,
    'visibility_required'                           => true,
    'whitespace_after_comma_in_array'               => true,
    'yoda_style'                                    => true,

    // end symfony set
    '@PhpCsFixer:risky'                  => true,
    'encoding'                           => true,
    'single_blank_line_before_namespace' => true,
    'blank_line_after_opening_tag'       => false,
    'strict_param'                       => true,
    'no_useless_else'                    => true,
    'no_useless_return'                  => true,
    'modernize_types_casting'            => true,
    'declare_strict_types'               => true,
    'dir_constant'                       => true,
    'no_whitespace_in_blank_line'        => true,

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
$config->setUsingCache(false);
$config->setLineEnding("\n");
$config->setFinder($finder);

return $config;
