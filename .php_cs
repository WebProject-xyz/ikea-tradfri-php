<?php
$header = <<<'EOF'
HEADER
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            'array_syntax' => ['syntax' => 'short'],
            'binary_operator_spaces' => [
                'align_double_arrow' => true,
                'align_equals' => false
            ],
            'blank_line_after_namespace' => true,
            'blank_line_before_statement' => [
                'statements' => [
                    'break',
                    'continue',
                    'return',
                    'throw',
                    'try',
                ],
            ],
            'braces' => true,
            'cast_spaces' => true,
            'concat_space' => ['spacing' => 'none'],
            'elseif' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'function_declaration' => true,
            /*'header_comment' => ['header' => $header, 'separate' => 'none'],*/
            'indentation_type' => true,
            'line_ending' => true,
            'lowercase_constants' => true,
            'lowercase_keywords' => true,
            'method_argument_space' => true,
            'native_function_invocation' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_closing_tag' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_consecutive_blank_lines' => true,
            'no_leading_namespace_whitespace' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_whitespace' => true,
            'no_unused_imports' => true,
            'no_whitespace_in_blank_line' => true,
            'phpdoc_align' => true,
            'phpdoc_indent' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_to_comment' => false,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_var_without_name' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_order' => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'phpdoc_types_order' => true,
            'return_assignment' => true,
            'semicolon_after_instruction' => true,
            'single_line_comment_style' => true,
            'strict_comparison' => true,
            'strict_param' => true,
            'string_line_ending' => true,
            'yoda_style' => true,
            'self_accessor' => true,
            'simplified_null_return' => false,
            'single_blank_line_at_eof' => true,
            'single_import_per_statement' => true,
            'single_line_after_imports' => true,
            'single_quote' => true,
            'ternary_operator_spaces' => true,
            'trim_array_spaces' => true,
            'visibility_required' => true,
            'ordered_imports' => true,
            'php_unit_strict' => true,
            'php_unit_test_class_requires_covers' => false,
            'ternary_to_null_coalescing' => true,
            'multiline_comment_opening_closing' => false,
            'native_constant_invocation' => true,
            'native_function_casing' => true,
            '@PHP71Migration' => false,
            'function_typehint_space' => true,
            'fully_qualified_strict_types' => true,
            'modernize_types_casting' => true,
            'return_type_declaration' => true,
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->name('*.php')
    );
