<?php
declare(strict_types=1);
$header = <<<'EOF'
HEADER
EOF;
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP56Migration' => true,
        'encoding' => true,
        'single_blank_line_at_eof' => true,
        'no_spaces_after_function_name' => true,
        'no_closing_tag' => true,
        'object_operator_without_whitespace' => true,
        'single_import_per_statement' => true,
        'method_argument_space' => true,
        'line_ending' => true,
        'no_alias_functions' => true,
        'no_empty_statement' => true,
        'indentation_type' => true,
        'blank_line_after_namespace' => true,
        'lowercase_keywords' => true,
        'no_spaces_inside_parenthesis' => true,
        'braces' => true,
        'no_trailing_whitespace' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'visibility_required' => true,
        'standardize_not_equals' => true,
        'full_opening_tag' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        // one should use PHPUnit methods to set up expected exception instead of annotations
        'general_phpdoc_annotation_remove' => ['expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp'],
       // 'header_comment' => ['header' => $header],
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'long'],
        'no_extra_consecutive_blank_lines' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'],
        'no_short_echo_tag' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => false,
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->ignoreDotFiles(true)
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
            ->exclude(__DIR__.'/vendor')
    )
    ;
