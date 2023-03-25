<?php

namespace Drupal\cloner\Plugin\Cloner;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base for all Cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
abstract class ClonerClonePluginBase extends PluginBase implements ClonerClonePluginBaseInterface, ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

}
