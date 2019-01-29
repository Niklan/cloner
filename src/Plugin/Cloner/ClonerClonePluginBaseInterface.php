<?php

namespace Drupal\cloner\Plugin\Cloner;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * The base interface for all Clone plugin instances.
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
interface ClonerClonePluginBaseInterface extends PluginInspectionInterface {

  /**
   * Processing cloning.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity_source
   *   The original entity.
   * @param \Drupal\Core\Entity\EntityInterface $entity_destination
   *   The destination entity.
   * @param array $context
   *   An array with context of clone. Can contain:
   *   - form_state: An instance of $form_state if called from form.
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []);

}
