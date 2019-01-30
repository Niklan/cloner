<?php

namespace Drupal\cloner_examples\Plugin\Cloner\ConfigEntity;

use Drupal\cloner\Annotation\ClonerConfigEntity;
use Drupal\cloner\Plugin\Cloner\ConfigEntity\ClonerConfigEntityClonePluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;

/**
 * @ClonerConfigEntity(
 *   id = "cloner_example_image_style",
 *   label = @Translation("Clone image style")
 * )
 */
class ImageStyle extends ClonerConfigEntityClonePluginBase {

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []) {

  }

}
