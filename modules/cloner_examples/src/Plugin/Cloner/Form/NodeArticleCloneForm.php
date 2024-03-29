<?php declare(strict_types = 1);

namespace Drupal\cloner_examples\Plugin\Cloner\Form;

use Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'cloner_examples_node_article_clone_form' entity cloner form.
 *
 * @ClonerForm(
 *   id = "cloner_examples_node_article_clone_form",
 *   label = @Translation("Clone node article"),
 *   cloner_plugin_type = "content_entity",
 *   cloner_plugin_id = "cloner_examples_node_article",
 *   entity_operation_label = @Translation("Clone")
 * )
 */
final class NodeArticleCloneForm extends ClonerFormPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity): bool {
    return $entity_type->id() === 'node' && $entity->bundle() === 'article';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $entity = $this->getEntity();

    $form['new_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title for cloned entity'),
      '#required' => TRUE,
      '#default_value' => $entity->label(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if ($form_state->getValue('new_title') === $this->getEntity()->label()) {
      $form_state->setErrorByName('new_title', $this->t('Title for new entity must be different from original.'));
    }
  }

}
