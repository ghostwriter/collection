<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Rector\Class_\ConstructClassMethodToSetUpTestCaseRector;
use Rector\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector;
use Rector\PHPUnit\Rector\Class_\TestListenerToHooksRector;
use Rector\PHPUnit\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\PHPUnit\Rector\ClassMethod\ExceptionAnnotationRector;
use Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector;
use Rector\PHPUnit\Rector\ClassMethod\TryCatchToExpectExceptionRector;
use Rector\PHPUnit\Rector\Foreach_\SimplifyForeachInstanceOfRector;
use Rector\PHPUnit\Rector\MethodCall\AssertCompareToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertComparisonToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertEqualsParameterToSpecificMethodsTypeRector;
use Rector\PHPUnit\Rector\MethodCall\AssertEqualsToSameRector;
use Rector\PHPUnit\Rector\MethodCall\AssertFalseStrposToContainsRector;
use Rector\PHPUnit\Rector\MethodCall\AssertInstanceOfComparisonRector;
use Rector\PHPUnit\Rector\MethodCall\AssertIssetToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertNotOperatorRector;
use Rector\PHPUnit\Rector\MethodCall\AssertPropertyExistsRector;
use Rector\PHPUnit\Rector\MethodCall\AssertRegExpRector;
use Rector\PHPUnit\Rector\MethodCall\AssertResourceToClosedResourceRector;
use Rector\PHPUnit\Rector\MethodCall\AssertSameBoolNullToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertSameTrueFalseToAssertTrueFalseRector;
use Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseInternalTypeToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertTrueFalseToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\CreateMockToCreateStubRector;
use Rector\PHPUnit\Rector\MethodCall\DelegateExceptionArgumentsRector;
use Rector\PHPUnit\Rector\MethodCall\ExplicitPhpErrorApiRector;
use Rector\PHPUnit\Rector\MethodCall\GetMockBuilderGetMockToCreateMockRector;
use Rector\PHPUnit\Rector\MethodCall\RemoveExpectAnyFromMockRector;
use Rector\PHPUnit\Rector\MethodCall\ReplaceAssertArraySubsetWithDmsPolyfillRector;
use Rector\PHPUnit\Rector\MethodCall\SpecificAssertContainsRector;
use Rector\PHPUnit\Rector\MethodCall\SpecificAssertContainsWithoutIdentityRector;
use Rector\PHPUnit\Rector\MethodCall\SpecificAssertInternalTypeRector;
use Rector\PHPUnit\Rector\MethodCall\UseSpecificWillMethodRector;
use Rector\PHPUnit\Rector\StaticCall\GetMockRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();
    $rectorConfig->parallel();
    $rectorConfig->sets([
        PHPUnitLevelSetList::UP_TO_PHPUNIT_50,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_60,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_70,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_80,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_90,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        LevelSetList::UP_TO_PHP_54,
        LevelSetList::UP_TO_PHP_55,
        LevelSetList::UP_TO_PHP_56,
        LevelSetList::UP_TO_PHP_70,
        LevelSetList::UP_TO_PHP_71,
        LevelSetList::UP_TO_PHP_72,
        LevelSetList::UP_TO_PHP_73,
        LevelSetList::UP_TO_PHP_74,
        LevelSetList::UP_TO_PHP_80,
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::PSR_4,
        SetList::TYPE_DECLARATION,
        SetList::TYPE_DECLARATION_STRICT,
        SetList::EARLY_RETURN,
        SetList::PHP_52,
        SetList::PHP_53,
        SetList::PHP_54,
        SetList::PHP_55,
        SetList::PHP_56,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
    ]);
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']);
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->skip([
        __DIR__ . '*/tests/Fixture/*',
        __DIR__ . '*/vendor/*',
        AddDoesNotPerformAssertionToNonAssertingTestRector::class,
        CallableThisArrayToAnonymousFunctionRector::class,
        PseudoNamespaceToNamespaceRector::class,
        RemoveEmptyClassMethodRector::class,
        RemoveEmptyTestMethodRector::class,
        StringClassNameToClassConstantRector::class,
        AddArrayReturnDocTypeRector::class,
    ]);
    $rectorConfig->rules([
        AddSeeTestAnnotationRector::class,
        AssertCompareToSpecificMethodRector::class,
        AssertComparisonToSpecificMethodRector::class,
        AssertEqualsParameterToSpecificMethodsTypeRector::class,
        AssertEqualsToSameRector::class,
        AssertFalseStrposToContainsRector::class,
        AssertInstanceOfComparisonRector::class,
        AssertIssetToSpecificMethodRector::class,
        AssertNotOperatorRector::class,
        AssertPropertyExistsRector::class,
        AssertRegExpRector::class,
        AssertResourceToClosedResourceRector::class,
        AssertSameBoolNullToSpecificMethodRector::class,
        AssertSameTrueFalseToAssertTrueFalseRector::class,
        AssertTrueFalseInternalTypeToSpecificMethodRector::class,
        AssertTrueFalseToSpecificMethodRector::class,
        ConstructClassMethodToSetUpTestCaseRector::class,
        CreateMockToCreateStubRector::class,
        DelegateExceptionArgumentsRector::class,
        ExceptionAnnotationRector::class,
        ExplicitPhpErrorApiRector::class,
        GetMockBuilderGetMockToCreateMockRector::class,
        GetMockRector::class,
        RemoveDataProviderTestPrefixRector::class,
        RemoveEmptyTestMethodRector::class,
        RemoveExpectAnyFromMockRector::class,
        ReplaceAssertArraySubsetWithDmsPolyfillRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        SimplifyForeachInstanceOfRector::class,
        SpecificAssertContainsRector::class,
        SpecificAssertContainsWithoutIdentityRector::class,
        SpecificAssertInternalTypeRector::class,
        TestListenerToHooksRector::class,
        TryCatchToExpectExceptionRector::class,
        TypedPropertyRector::class,
        UseSpecificWillMethodRector::class,
    ]);
};
