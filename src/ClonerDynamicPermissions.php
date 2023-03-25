<?php

declare(strict_types = 1);

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
final class ClonerDynamicPermissions implements ContainerInjectionInterface {

  /**
   * ClonerDynamicPermissions constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\StringTranslation\TranslationManager $translationManager
   *   The translation manager.
   */
  public function __construct(
      protected EntityTypeManagerInterface $entityTypeManager,
      protected TranslationManager $translationManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager'),
      $container->get('string_translation'),
    );
  }

  /**
   * Returns an array of cloner permissions.
   *
   * @return array
   *   An array with permissions.
   */
  public function permissions(): array {
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
