<?php

namespace Drupal\matomo_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for system_test routes.
 */
class MatomoTestController extends ControllerBase {

  /**
   * Tests setting messages and removing one before it is displayed.
   *
   * @return array
   *   Empty array, we just test the setting of messages.
   */
  public function drupalAddMessageTest() {
    // Set some messages.
    drupal_set_message($this->t('Example status message.'), 'status');
    drupal_set_message($this->t('Example warning message.'), 'warning');
    drupal_set_message($this->t('Example error message.'), 'error');
    drupal_set_message($this->t('Example error <em>message</em> with html tags and <a href="http://example.com/">link</a>.'), 'error');

    return [];
  }

}
