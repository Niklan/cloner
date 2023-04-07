<?php declare(strict_types = 1);

namespace Drupal\cloner\Hook\Entity;

use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides entity operations hook.
 *
 * @see cloner_entity_operation()
 */
final class EntityOperation implements ContainerInjectionInterface {

  /**
   * Construct EntityOperation object.
   *
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $clonerPluginManager
   *   The cloner plugin manager.
   */
  public function __construct(
    protected ClonerPluginManager $clonerPluginManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('plugin.manager.cloner.form'),
    );
  }

  /**
   * Implements hook_entity_operation().
   */
  public function __invoke(EntityInterface $entity): array {
    $operations = [];

    $entity_type = $entity->getEntityType();
    $applicable_plugins = $this->clonerPluginManager->isApplicable($entity_type, $entity);

    if (\count($applicable_plugins) > 0) {
      $plugin_definition = \array_shift($applicable_plugins);

      if (isset($plugin_definition['entity_operation_label'])) {
        $operations['cloner'] = [
          'title' => $plugin_definition['entity_operation_label'],
          'weight' => 50,
          'url' => $entity->toUrl('cloner-form'),
        ];
      }
    }

    return $operations;
  }

}
