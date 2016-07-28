<?php

namespace Drupal\payment\Plugin\Payment\Type;

use Drupal\plugin\PluginDefinition\ArrayPluginDefinitionDecorator;

/**
 * Provides a payment type plugin definition.
 *
 * @ingroup Plugin
 */
class PaymentTypeDefinition extends ArrayPluginDefinitionDecorator {

  /**
   * Gets the configuration form's class name.
   *
   * @return string|null
   *   The fully qualified class name of the payment type's global configuration
   *   form, or NULL if no form exists.
   */
  public function getConfigurationFormClass() {
    return isset($this->arrayDefinition['configuration_form']) ? $this->arrayDefinition['configuration_form'] : NULL;
  }

}
