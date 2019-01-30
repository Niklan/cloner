<?php

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
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * ClonerPluginBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {

    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param $key
   *
   * @return bool|string
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getDefinitionKey(EntityInterface $entity, $key) {
    return $this->entityTypeManager
      ->getDefinition($entity->getEntityTypeId())
      ->getKey($key);
  }

}
