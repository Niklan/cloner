<?php

namespace Drupal\cloner\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Plugin\Cloner\ClonerPluginBase;

/**
 * The base for ContentEntity cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner\ContentEntity
 */
abstract class ClonerContentEntityPluginBase extends ClonerPluginBase implements ClonerContentEntityPluginBaseInterface{

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle() {
    return $this->entity->bundle();
  }

}
