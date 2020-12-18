<?php

namespace Drupal\cloner\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Route;

/**
 * Cloner event subscriber.
 */
class ClonerSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
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
  public function alterRoutes(RouteBuildEvent $event) {
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
   * @return \Symfony\Component\Routing\Route
   *   The route for cloner form, NULL otherwise.
   */
  protected function prepareRoute($entity_definition) {
    if ($cloner_form_route_template = $entity_definition->getLinkTemplate('cloner-form')) {
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

}
