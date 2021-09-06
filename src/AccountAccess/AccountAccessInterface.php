<?php
declare(strict_types=1);

namespace Drupal\renderkit\AccountAccess;

use Drupal\Core\Session\AccountInterface;

interface AccountAccessInterface {

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return \Drupal\Core\Access\AccessResult
   */
  public function access(AccountInterface $account);

}
