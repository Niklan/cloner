<?php

namespace Drupal\cloner\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;

/**
 * Checks access for cloner form.
 */
class AccessClonerForm implements AccessInterface {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * AccessClonerForm constructor.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match.
   */
  public function __construct(CurrentRouteMatch $current_route_match) {
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account) {
    $entity_type_id = $this
      ->currentRouteMatch
      ->getRouteObject()
      ->getOption('_cloner_entity_type_id');

    $permissions = [
      'access all entity cloner',
      "access $entity_type_id cloner",
    ];

    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR');
  }

}
