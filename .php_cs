<?php

$finder = PhpCsFixer\Finder::create()
    ->in('spec')
    ->in('src')
    ->in('tests')
    ->notName('*.xml');

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals' => true
        ],
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'declare_strict_types' => true

    ))
    ->setFinder($finder)
;
