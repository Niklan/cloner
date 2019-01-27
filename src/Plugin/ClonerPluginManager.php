<?php

namespace Drupal\cloner\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Factory\ContainerFactory;
use Symfony\Component\DependencyInjection\Container;

/**
 * Cloner plugin manager.
 */
class ClonerPluginManager extends DefaultPluginManager {

  /**
   * Cloner Plugin Manager constructor.
   *
   * @params string $cloner_plugin_type
   *   The cloner plugin type.
   * @param \Traversable $namespaces
   *   The namespaces.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct($cloner_plugin_type, \Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {

    // content_entity => ContentEntity, config_entity => ConfigEntity.
    $cloner_plugin_type_camelized = Container::camelize($cloner_plugin_type);
    $plugin_dir = "Plugin/Cloner/{$cloner_plugin_type_camelized}";
    $plugin_interface = "Drupal\cloner\Plugin\Cloner\{$cloner_plugin_type_camelized}\Cloner{$cloner_plugin_type_camelized}PluginInterface";
    $plugin_definition_annotation_name = "Drupal\cloner\Annotation\Cloner{$cloner_plugin_type_camelized}";

    parent::__construct($plugin_dir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    $this->defaults += [
      'plugin_type' => $cloner_plugin_type,
      'enabled' => TRUE,
      'weight' => 0,
    ];

    // Register cloner_plugin_PLUGIN_TYPE_alter().
    $this->alterInfo("cloner_plugin_{$cloner_plugin_type_camelized}");

    // Set cache.
    $this->setCacheBackend($cache_backend, "cloner:{$cloner_plugin_type}");

    // Use container factory instead of DefaultFactory to support Dependency
    // Injection of services.
    $this->factory = new ContainerFactory($this->getDiscovery());
  }

}