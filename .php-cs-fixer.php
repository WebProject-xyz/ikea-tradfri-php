<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

use Ergebnis\License;
use Ergebnis\PhpCsFixer;

require_once __DIR__ . '/vendor/autoload.php';

$license = License\Type\MIT::markdown(
    __DIR__ . '/LICENSE.md',
    License\Range::since(
        License\Year::fromString('2024'),
        new DateTimeZone('Europe/Berlin'),
    ),
    License\Holder::fromString('Benjamin Fahl'),
    License\Url::fromString('https://github.com/WebProject-xyz/ikea-tradfri-php'),
);

$license->save();

$ruleSet = PhpCsFixer\Config\RuleSet\Php82::create()
    ->withHeader($license->header())
    ->withRules(PhpCsFixer\Config\Rules::fromArray(
        [
            'binary_operator_spaces' => [
                'default'   => 'align',
                'operators' => [
                    '??' => 'single_space',
                ],
            ],
            'blank_line_before_statement' => [
                'statements' => [
                    'return',
                ],
            ],
            'php_unit_construct' => [
                'assertions' => [
                    'assertEquals',
                    'assertNotEquals',
                    'assertNotSame',
                    'assertSame',
                ],
            ],
            'php_unit_data_provider_name'        => true,
            'php_unit_data_provider_return_type' => true,
            'php_unit_data_provider_static'      => [
                'force' => true,
            ],
            'php_unit_fqcn_annotation' => true,
            'php_unit_internal_class'  => false,
            'php_unit_method_casing'   => [
                'case' => 'camel_case',
            ],
            'php_unit_mock_short_will_return'      => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_test_annotation'             => [
                'style' => 'prefix',
            ],
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'this',
            ],
        ],
    ));

$config = PhpCsFixer\Config\Factory::fromRuleSet($ruleSet);
$config->getFinder()
    ->exclude([
        'build/',
        '.github/',
        'var/',
    ])
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->name([
        '.php-cs-fixer.php',
        'console',
    ])
    ->notName([
        '.env.local.php',
    ]);

$config->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

return $config;
