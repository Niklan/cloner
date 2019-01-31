# Cloner plugins

Cloner define three new plugins for developers:

 1. **ClonerContentEntity** for handle cloning of content entities.
 2. **ClonerConfigEntity** for handle cloning of configuration entities.
 3. **CloneForm** to add cloning form to the UI and pass some data to other plugins.

All three plugins supports Dependency Injection by default.

## @ClonerContentEntity

Annotations properties:

 * **id**: (required) The unique identifier of the plugin.
 * **label**: (required) The plugin label.

Example of annotation.

```php
/**
 * @ClonerContentEntity(
 *   id = "cloner_examples_node_article",
 *   label = @Translation("Clone node article"),
 * )
 */
```

Plugins of this type must be created in **src/Plugin/Cloner/ContentEntity** folder.

Plugin object must extend `ClonerContentEntityClonePluginBase`.

Plugin of this type has only one existed and required method: `public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = [])`

 * `EntityInterface $entity_source`: Entity object which is the source of cloning (which cloning).
 * `EntityInterface $entity_destination`: Entity object duplicate, created with `createDuplicate()`, which will be resulted entity.
 * `array $context = []`: The additional data passed to clone. F.e. if plugin will be called via ClonerForm plugins form, there will be `$context['form_state`]` with `FormStateInterface` object of submitted form.

Example of the plugin.

```php
<?php

namespace Drupal\cloner_examples\Plugin\Cloner\ContentEntity;

use Drupal\cloner\Annotation\ClonerContentEntity;
use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;

/**
 * @ClonerContentEntity(
 *   id = "cloner_examples_node",
 *   label = @Translation("Clone node article"),
 * )
 */
class NodeClone extends ClonerContentEntityClonePluginBase {

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []) {
    // If executed via ClonerForm.
    // @see \Drupal\cloner_examples\Plugin\Cloner\Form\NodeArticleCloneForm
    if (isset($context['form_state'])) {
      /** @var \Drupal\Core\Form\FormStateInterface $form_state */
      $form_state = $context['form_state'];
      $entity_destination->setTitle($form_state->getValue('new_title'));
    }
  }

}
```

## @ClonerConfigEntity

Annotations properties:

 * **id**: (required) The unique identifier of the plugin.
 * **label**: (required) The plugin label.

Example of annotation.

```php
/**
 * @ClonerConfigEntity(
 *   id = "cloner_examples_image_style",
 *   label = @Translation("Clone image style"),
 * )
 */
```

Plugins of this type must be created in **src/Plugin/Cloner/ConfigEntity** folder.

Plugin object must extend `ClonerConfigEntityClonePluginBase`.

Plugin of this type requires method: `public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = [])`

 * `EntityInterface $entity_source`: Entity object which is the source of cloning (which cloning).
 * `EntityInterface $entity_destination`: Entity object duplicate, created with `createDuplicate()`, which will be resulted entity.
 * `array $context = []`: The additional data passed to clone. F.e. if plugin will be called via ClonerForm plugins form, there will be `$context['form_state`]` with `FormStateInterface` object of submitted form.

**Important!** Configuration entities have string id's, and you must set new unique id for `$entity_destionation`.

The base plugin `ClonerConfigEntityClonePluginBase` adds method which can help you with that.

 * `getDefinitionKey($entity, $key)`: By passing entity (actually not matter which, source or destination, because entity type will be the same) and needed key, it returns the value.

Example of the plugin.

```php
<?php

namespace Drupal\cloner_examples\Plugin\Cloner\ConfigEntity;

use Drupal\cloner\Annotation\ClonerConfigEntity;
use Drupal\cloner\Plugin\Cloner\ConfigEntity\ClonerConfigEntityClonePluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;

/**
 * @ClonerConfigEntity(
 *   id = "cloner_example_image_style",
 *   label = @Translation("Clone image style")
 * )
 */
class ImageStyleClone extends ClonerConfigEntityClonePluginBase {

  /**
   * {@inheritdoc}
   */
  public function cloneEntity(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []) {
    $id_key = $this->getDefinitionKey($entity_source, 'id');
    $label_key = $this->getDefinitionKey($entity_source, 'label');

    if (isset($context['form_state'])) {
      /** @var \Drupal\Core\Form\FormStateInterface $form_state */
      $form_state = $context['form_state'];
      $destination_id = $form_state->getValue('machine_name');
      $label = $form_state->getValue('new_title');
    }
    else {
      $destination_id = $entity_source->id() . '_cloned';
      $label = $entity_source->label();
    }

    // Set new id is required for config entities.
    $entity_destination->set($id_key, $destination_id);
    $entity_destination->set($label_key, $label);
  }

}

```

## @ClonerForm

Annotations properties:

 * **id**: (required) The unique identifier of the plugin.
 * **label**: (required) The plugin label.
 * **cloner_plugin_type**: (required) Cloner plugin type which will be used for clone on submit. Must be `config_entity` or `content_entity`.
 * **cloner_plugin_id**: (required) The cloner plugin id of specific plugin type. This plugin will be called on submit and `$form_state` will be passed in `$context`.
 * **entity_operation_label**: (optional) The operation label. If set, link to this clone form with this label will be added to entities where this plugin `isApplicable()`.
 * **enabled**: (optional) Default: TRUE. Whether this plugin enable or not. Can be helpful if you want temporary disable form but leave it in codebase.
 * **weight**: (optional) Default: 0. The weight of the plugin. If multiple plugins will be applicable via `isApplicable()`, the one with higher weight will be used.

Example of annotation.

```php
/**
 * @ClonerForm(
 *   id = "cloner_examples_node_article",
 *   label = @Translation("Clone node article"),
 *   cloner_plugin_type = "content_entity",
 *   cloner_plugin_id = "cloner_examples_node_article",
 *   entity_operation_label = @Translation("Clone")
 * )
 */
```

Plugins of this type must be created in **src/Plugin/Cloner/Form** folder

Methods bu this plugin:

 * `isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity)`: (optional) Default: TRUE. Determine is this plugin will be available for clone specific entity type and entity instance.
 * `buildForm(array $form, FormStateInterface $form_state)`: (required) Returns form array with needed form.
 * `validateForm(array &$form, FormStateInterface $form_state)`: (optional) If you want some validation, use it.

Plugin example:

```php
<?php

namespace Drupal\cloner_examples\Plugin\Cloner\Form;

use Drupal\cloner\Annotation\ClonerForm;
use Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ClonerForm(
 *   id = "cloner_examples_node_article_clone_form",
 *   label = @Translation("Clone node article"),
 *   cloner_plugin_type = "content_entity",
 *   cloner_plugin_id = "cloner_examples_node_article",
 *   entity_operation_label = @Translation("Clone")
 * )
 */
class NodeArticleCloneForm extends ClonerFormPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity) {
    return $entity_type->id() == 'node' && $entity->bundle() == 'article';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('new_title') == $this->getEntity()->label()) {
      $form_state->setErrorByName('new_title', t('Title for new entity must be different from original.'));
    }
  }

}
```