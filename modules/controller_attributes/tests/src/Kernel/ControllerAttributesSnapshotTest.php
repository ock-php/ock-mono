<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Kernel;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteAccessPublic;
use Drupal\controller_attributes\Attribute\RouteActionLink;
use Drupal\controller_attributes\Attribute\RouteDefaults;
use Drupal\controller_attributes\Attribute\RouteDefaultTaskLink;
use Drupal\controller_attributes\Attribute\RouteHttpMethod;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteOptions;
use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\controller_attributes\Attribute\RouteRequirements;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTaskLink;
use Drupal\controller_attributes\Attribute\RouteTitle;
use Drupal\controller_attributes\Attribute\RouteTitleMethod;
use Drupal\controller_attributes\ControllerAttributesRouteProvider;
use Drupal\controller_attributes\Hook\LinksFromRoutes;
use Ock\DrupalTesting\ModuleSnapshotTestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ControllerAttributesRouteProvider::class)]
#[CoversClass(LinksFromRoutes::class)]
#[CoversClass(Route::class)]
#[CoversClass(RouteAccessPublic::class)]
#[CoversClass(RouteActionLink::class)]
#[CoversClass(RouteDefaults::class)]
#[CoversClass(RouteDefaultTaskLink::class)]
#[CoversClass(RouteHttpMethod::class)]
#[CoversClass(RouteIsAdmin::class)]
#[CoversClass(RouteMenuLink::class)]
#[CoversClass(RouteOptions::class)]
#[CoversClass(RouteParameters::class)]
#[CoversClass(RouteRequirements::class)]
#[CoversClass(RouteRequirePermission::class)]
#[CoversClass(RouteTaskLink::class)]
#[CoversClass(RouteTitle::class)]
#[CoversClass(RouteTitleMethod::class)]
class ControllerAttributesSnapshotTest extends ModuleSnapshotTestBase {

  /**
   * {@inheritdoc}
   */
  protected static function getTestedModuleNames(): array {
    return [
      'controller_attributes',
      'controller_attributes_test',
    ];
  }

}
