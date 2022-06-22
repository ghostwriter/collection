<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
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
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();
    $rectorConfig->parallel();
    $rectorConfig->sets([
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        //        DowngradeLevelSetList::DOWN_TO_PHP_80,
        DowngradeSetList::PHP_81,
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
        SetList::PHP_81,
        SetList::RECTOR_CONFIG,
    ]);
    $rectorConfig->paths([
        __DIR__ . '/compliance.php',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->skip([
        __DIR__ . '*/tests/Fixture/*',
        __DIR__ . '*/vendor/*',
        CallableThisArrayToAnonymousFunctionRector::class,
        PseudoNamespaceToNamespaceRector::class,
        StringClassNameToClassConstantRector::class,
        AddDoesNotPerformAssertionToNonAssertingTestRector::class,
    ]);
    // prefer self:: over $this for phpunit
    $rectorConfig->ruleWithConfiguration(
        PreferThisOrSelfMethodCallRector::class,
        [
            TestCase::class => PreferenceSelfThis::PREFER_SELF(),
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            new MethodCallRename(TestCase::class, 'setExpectedException', 'expectedException'),
            new MethodCallRename(TestCase::class, 'setExpectedExceptionRegExp', 'expectedException'),
        ]
    );
    // register single rule
    $rectorConfig->rule(TypedPropertyRector::class);
    $rectorConfig->rule(RestoreDefaultNullToNullableTypePropertyRector::class);
    $rectorConfig->rule(AddSeeTestAnnotationRector::class);
    $rectorConfig->rule(AssertCompareToSpecificMethodRector::class);
    $rectorConfig->rule(AssertComparisonToSpecificMethodRector::class);
    $rectorConfig->rule(AssertEqualsParameterToSpecificMethodsTypeRector::class);
    $rectorConfig->rule(AssertEqualsToSameRector::class);
    $rectorConfig->rule(AssertFalseStrposToContainsRector::class);
    $rectorConfig->rule(AssertInstanceOfComparisonRector::class);
    $rectorConfig->rule(AssertIssetToSpecificMethodRector::class);
    $rectorConfig->rule(AssertNotOperatorRector::class);
    $rectorConfig->rule(AssertPropertyExistsRector::class);
    $rectorConfig->rule(AssertRegExpRector::class);
    $rectorConfig->rule(AssertResourceToClosedResourceRector::class);
    $rectorConfig->rule(AssertSameBoolNullToSpecificMethodRector::class);
    $rectorConfig->rule(AssertSameTrueFalseToAssertTrueFalseRector::class);
    $rectorConfig->rule(AssertTrueFalseInternalTypeToSpecificMethodRector::class);
    $rectorConfig->rule(AssertTrueFalseToSpecificMethodRector::class);
    $rectorConfig->rule(ConstructClassMethodToSetUpTestCaseRector::class);
    $rectorConfig->rule(CreateMockToCreateStubRector::class);
    $rectorConfig->rule(DelegateExceptionArgumentsRector::class);
    $rectorConfig->rule(ExceptionAnnotationRector::class);
    $rectorConfig->rule(ExplicitPhpErrorApiRector::class);
    $rectorConfig->rule(GetMockBuilderGetMockToCreateMockRector::class);
    $rectorConfig->rule(GetMockRector::class);
    $rectorConfig->rule(RemoveDataProviderTestPrefixRector::class);
    $rectorConfig->rule(RemoveEmptyTestMethodRector::class);
    $rectorConfig->rule(RemoveExpectAnyFromMockRector::class);
    $rectorConfig->rule(ReplaceAssertArraySubsetWithDmsPolyfillRector::class);
    $rectorConfig->rule(SimplifyForeachInstanceOfRector::class);
    $rectorConfig->rule(SpecificAssertContainsRector::class);
    $rectorConfig->rule(SpecificAssertContainsWithoutIdentityRector::class);
    $rectorConfig->rule(SpecificAssertInternalTypeRector::class);
    $rectorConfig->rule(TestListenerToHooksRector::class);
    $rectorConfig->rule(TryCatchToExpectExceptionRector::class);
    $rectorConfig->rule(UseSpecificWillMethodRector::class);
};
