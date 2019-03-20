CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------

**Cloner** let you clone any entity on Drupal site, and provide forms for this as well.

The obvious question that can appear, what's the difference between Cloner and [Entity Clone](https://www.drupal.org/project/entity_clone)?

Cloner is inspired by **Entity Clone**, but uses a different approach to achieve the result. There is a list of things you need to know, why it exists, and what's make it different:

 * Cloner is targeted primarily **for developers**.
 * Cloner is utilizing powerful Drupal 8 Plugin system.
 * Cloner **do not** add clone support for any entity type on site. You must write it manually.
 * Cloner **do not** add operations to entities unless you create ClonerForm plugin and ask for it.
 * Cloner have a separate plugin for UI, and clone possibilities, to expose it to content managers.
 * With Cloner you can create as many Cloner plugins, as you want, even for the same entity type and bundle combination.
 * With Cloner, you write how an entity must be cloned and all behavior, there is now predefined cloners.
 * You can call Cloner clone plugins directly in the code and utilize them.

So, this means Entity Clone is the more user-friendly module, which works with zero-configuration, but has drawbacks, if you have very complex entities and fields in it. Cloner, on the other hand, will not do anything, before the developer does something. This requires time to write the cloning process, as a reward, you will get full control over cloning, and every complex entity type can be handled easily.

REQUIREMENTS
------------

This module requires no modules outside of the Drupal core.


INSTALLATION
------------

 * Install the Cloner module as you would normally install a contributed
   Drupal module. Visit https://www.drupal.org/node/1897420 for further
   information.


CONFIGURATION
-------------

There is no UI to configure the module out of the box. This module requires you to write a bit of code. For further information look at docs folder or cloner_examples module shipped with it.


MAINTAINERS
-----------

 * Nikita Malyshev (Niklan) - https://www.drupal.org/u/niklan
