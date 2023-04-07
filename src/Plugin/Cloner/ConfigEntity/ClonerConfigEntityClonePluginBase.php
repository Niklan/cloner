<?php declare(strict_types = 1);

namespace Drupal\cloner\Plugin\Cloner\ConfigEntity;

use Drupal\cloner\Plugin\Cloner\ClonerClonePluginBase;
use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBaseInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base for ConfigEntity cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner\ContentEntity
 */
abstract class ClonerConfigEntityClonePluginBase extends ClonerClonePluginBase implements ClonerContentEntityClonePluginBaseInterface {

  /**
   * The entity type manager.
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * Gets definition key.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   * @param string $key
   *   The specific entity key.
   *
   * @return bool|string
   *   The entity key, or FALSE if it does not exist.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getDefinitionKey(EntityInterface $entity, string $key): string|bool {
    return $this->entityTypeManager
      ->getDefinition($entity->getEntityTypeId())
      ->getKey($key);
  }

}
