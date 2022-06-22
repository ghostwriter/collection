<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use Ghostwriter\Compliance\ValueObject\Tool;

return static function (ComplianceConfiguration $complianceConfiguration): void {
//    $complianceConfig->phpVersion(PhpVersion::CURRENT_STABLE);
    $complianceConfiguration->phpVersion(PhpVersion::CURRENT_LATEST);
    $complianceConfiguration->skip([
        PhpVersion::PHP_82,
        Tool::CODECEPTION => [PhpVersion::PHP_80],
        Tool::COMPOSER_REQUIRE_CHECKER => [PhpVersion::PHP_81],
        Tool::EASY_CODING_STANDARD => [PhpVersion::PHP_81],
        Tool::GRUMPHP => [PhpVersion::PHP_82],
        Tool::INFECTION => [PhpVersion::PHP_82],
        Tool::MARKDOWNLINT => [PhpVersion::PHP_82],
        Tool::PHAN => [PhpVersion::PHP_82],
        Tool::PHPBENCH => [PhpVersion::PHP_82],
        Tool::PHPCS => [PhpVersion::PHP_82],
        Tool::PHP_CS_FIXER => [PhpVersion::PHP_82],
        Tool::PHPUNIT => [PhpVersion::PHP_82],
        Tool::PSALM => [PhpVersion::PHP_82],
        Tool::RECTOR => [PhpVersion::PHP_82],
        //        Tool::PHP_MESS_DETECTOR => [PhpVersion::PHP_82],
        //        Tool::PHP_METRICS => [PhpVersion::PHP_82],
        //        Tool::PHPSTAN => [PhpVersion::PHP_82],
    ]);
};
