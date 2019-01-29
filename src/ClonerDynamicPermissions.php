<?php

namespace Drupal\cloner;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\TranslationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create permissions for each entity cloner.
 *
 * @todo maybe generate permissions only for entities which has plugins, or per
 * plugin permissions.
 */
class ClonerDynamicPermissions implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The translation manager.
   *
   * @var \Drupal\Core\StringTranslation\TranslationManager
   */
  protected $translationManager;

  /**
   * ClonerDynamicPermissions constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity type manager.
   * @param \Drupal\Core\StringTranslation\TranslationManager $string_translation
   *   The translation manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, TranslationManager $string_translation) {
    $this->entityTypeManager = $entity_manager;
    $this->translationManager = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('string_translation')
    );
  }

  /**
   * Returns an array of cloner permissions.
   *
   * @return array
   *   An array with permissions.
   */
  public function permissions() {
    $permissions = [];

    foreach ($this->entityTypeManager->getDefinitions() as $entity_type_id => $entity_type) {
      $permission_id = "access $entity_type_id cloner";
      $permissions[$permission_id] = $this->translationManager->translate(
        'Access to <em>@label</em> cloner entity form', [
        '@label' => $entity_type->getLabel(),
      ]);
    }

    return $permissions;
  }

}
