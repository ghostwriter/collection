<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedInterfacesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use PhpCsFixer\Fixer\FunctionNotation\UseArrowFunctionsFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\GroupImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\LanguageConstruct\GetClassToClassKeywordFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAnnotationWithoutDotFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitNamespacedFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitNoExpectationAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSizeClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->parallel();
    $ecsConfig->paths([
        __DIR__ . '/compliance.php',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->skip([
        '*/tests/Fixture/*',
        '*/vendor/*',
        GroupImportFixer::class,
        BinaryOperatorSpacesFixer::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
        PhpdocLineSpanFixer::class,
        PhpdocTrimFixer::class,
    ]);
    $ecsConfig->import(SetList::ARRAY);
    $ecsConfig->import(SetList::CLEAN_CODE);
    $ecsConfig->import(SetList::COMMON);
    $ecsConfig->import(SetList::CONTROL_STRUCTURES);
    $ecsConfig->import(SetList::NAMESPACES);
    $ecsConfig->import(SetList::PSR_12);
    $ecsConfig->import(SetList::DOCBLOCK);
    $ecsConfig->import(SetList::PHPUNIT);
    $ecsConfig->import(SetList::SPACES);
    $ecsConfig->import(SetList::STRICT);
    $ecsConfig->import(SetList::SYMPLIFY);

    $parameters = $ecsConfig->parameters();
    $parameters->set(Option::CACHE_DIRECTORY, __DIR__ . '/.cache/ecs');

    $ecsConfig->ruleWithConfiguration(GlobalNamespaceImportFixer::class, [
        'import_classes' => true,
        'import_constants' => true,
        'import_functions' => true,
    ]);
    $ecsConfig->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => ['class', 'const', 'function'],
    ]);
    $ecsConfig->ruleWithConfiguration(PhpdocAlignFixer::class, [
        'tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var'],
    ]);
    $ecsConfig->ruleWithConfiguration(PhpUnitTestCaseStaticMethodCallsFixer::class, [
        'call_type' => 'self',
    ]);
    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);
    $ecsConfig->ruleWithConfiguration(ConstantCaseFixer::class, [
        'case' => 'lower',
    ]);
    $ecsConfig->ruleWithConfiguration(OrderedClassElementsFixer::class, [
        'sort_algorithm' => 'alpha',
    ]);
    $ecsConfig->ruleWithConfiguration(OrderedInterfacesFixer::class, [
        'order' => 'alpha',
    ]);

    $ecsConfig->rule(LowercaseKeywordsFixer::class);
    $ecsConfig->rule(LowercaseStaticReferenceFixer::class);
    $ecsConfig->rule(MagicConstantCasingFixer::class);
    $ecsConfig->rule(MagicMethodCasingFixer::class);
    $ecsConfig->rule(FinalClassFixer::class);
    $ecsConfig->rule(ProtectedToPrivateFixer::class);
    $ecsConfig->rule(SelfAccessorFixer::class);
    $ecsConfig->rule(SelfStaticAccessorFixer::class);
    $ecsConfig->rule(SingleClassElementPerStatementFixer::class);
    $ecsConfig->rule(VisibilityRequiredFixer::class);
    $ecsConfig->rule(ElseifFixer::class);
    $ecsConfig->rule(NoSuperfluousElseifFixer::class);
    $ecsConfig->rule(SimplifiedIfReturnFixer::class);
    $ecsConfig->rule(YodaStyleFixer::class);
    $ecsConfig->rule(ReturnTypeDeclarationFixer::class);
    $ecsConfig->rule(StaticLambdaFixer::class);
    $ecsConfig->rule(UseArrowFunctionsFixer::class);
    $ecsConfig->rule(FullyQualifiedStrictTypesFixer::class);
    $ecsConfig->rule(NoLeadingImportSlashFixer::class);
    $ecsConfig->rule(NoUnusedImportsFixer::class);
    $ecsConfig->rule(SingleImportPerStatementFixer::class);
    $ecsConfig->rule(GetClassToClassKeywordFixer::class);
    $ecsConfig->rule(NoHomoglyphNamesFixer::class);
    $ecsConfig->rule(PhpdocAnnotationWithoutDotFixer::class);
    $ecsConfig->rule(PhpdocOrderFixer::class);
    $ecsConfig->rule(PhpdocSeparationFixer::class);
    $ecsConfig->rule(PhpdocSummaryFixer::class);
    $ecsConfig->rule(PhpdocTypesOrderFixer::class);
    $ecsConfig->rule(PhpUnitConstructFixer::class);
    $ecsConfig->rule(PhpUnitDedicateAssertFixer::class);
    $ecsConfig->rule(PhpUnitDedicateAssertInternalTypeFixer::class);
    $ecsConfig->rule(PhpUnitExpectationFixer::class);
    $ecsConfig->rule(PhpUnitFqcnAnnotationFixer::class);
    $ecsConfig->rule(PhpUnitInternalClassFixer::class);
    $ecsConfig->rule(PhpUnitMethodCasingFixer::class);
    $ecsConfig->rule(PhpUnitMockFixer::class);
    $ecsConfig->rule(PhpUnitMockShortWillReturnFixer::class);
    $ecsConfig->rule(PhpUnitNamespacedFixer::class);
    $ecsConfig->rule(PhpUnitNoExpectationAnnotationFixer::class);
    $ecsConfig->rule(PhpUnitSetUpTearDownVisibilityFixer::class);
    $ecsConfig->rule(PhpUnitSizeClassFixer::class);
    $ecsConfig->rule(PhpUnitStrictFixer::class);
    $ecsConfig->rule(PhpUnitTestAnnotationFixer::class);
    $ecsConfig->rule(PhpUnitTestClassRequiresCoversFixer::class);
    $ecsConfig->rule(NoEmptyStatementFixer::class);
    $ecsConfig->rule(NoSinglelineWhitespaceBeforeSemicolonsFixer::class);
    $ecsConfig->rule(SemicolonAfterInstructionFixer::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(StrictComparisonFixer::class);
    $ecsConfig->rule(StrictParamFixer::class);
};
