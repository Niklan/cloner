<?php declare(strict_types = 1);

namespace Drupal\cloner\EventSubscriber;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Route;

/**
 * Cloner event subscriber.
 */
final class ClonerSubscriber implements EventSubscriberInterface {

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      RoutingEvents::ALTER => ['alterRoutes'],
    ];
  }

  /**
   * Alter routes.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route build event.
   */
  public function alterRoutes(RouteBuildEvent $event): void {
    $route_collection = $event->getRouteCollection();
    $entity_definitions = $this->entityTypeManager->getDefinitions();

    foreach ($entity_definitions as $entity_type_id => $entity_definition) {
      if ($route = $this->prepareRoute($entity_definition)) {
        $route_collection->add("entity.{$entity_type_id}.cloner_form", $route);
      }
    }
  }

  /**
   * Prepare route for cloner form page.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_definition
   *   The entity definition.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The route for cloner form, NULL otherwise.
   */
  protected function prepareRoute(EntityTypeInterface $entity_definition): ?Route {
    $cloner_form_route_template = $entity_definition->getLinkTemplate('cloner-form');

    if (!$cloner_form_route_template) {
      return NULL;
    }

    $entity_type_id = $entity_definition->id();
    // /cloner/entity_type_id/{entity_type_id}
    // @see cloner_entity_type_alter().
    $cloner_route = new Route($cloner_form_route_template);
    $cloner_route
      ->addDefaults([
        '_form' => '\Drupal\cloner\Form\ClonerCloneForm',
        '_title' => 'Clone',
      ])
      ->addRequirements([
        '_access_cloner_form' => 'TRUE',
      ])
      ->setOption('_admin_route', TRUE)
      // Save entity type id for further easy definition in clone form.
      ->setOption('_cloner_entity_type_id', $entity_type_id)
      // Set param type to get entity object, instead of raw id.
      ->setOption('parameters', [
        $entity_type_id => [
          'type' => 'entity:' . $entity_type_id,
        ],
      ]);

    return $cloner_route;
  }

}
