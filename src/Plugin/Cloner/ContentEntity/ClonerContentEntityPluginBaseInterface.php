<?php

namespace Drupal\cloner\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Plugin\Cloner\ClonerPluginInterface;

/**
 * The base for ContentEntity cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner\ContentEntity
 */
interface ClonerContentEntityPluginBaseInterface extends ClonerPluginInterface {

  /**
   * Gets cloned content entity bundle.
   *
   * @return string
   *   The entity bundle name.
   */
  public function getEntityBundle();

}
