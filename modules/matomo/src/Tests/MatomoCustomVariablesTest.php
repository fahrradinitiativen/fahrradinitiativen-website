<?php

namespace Drupal\matomo\Tests;

use Drupal\Component\Serialization\Json;
use Drupal\simpletest\WebTestBase;

/**
 * Test custom variables functionality of Matomo module.
 *
 * @group Matomo
 */
class MatomoCustomVariablesTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['matomo', 'token'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $permissions = [
      'access administration pages',
      'administer matomo',
    ];

    // User to set up matomo.
    $this->admin_user = $this->drupalCreateUser($permissions);
  }

  /**
   * Tests if custom variables are properly added to the page.
   */
  public function testMatomoCustomVariables() {
    $site_id = '3';
    $this->config('matomo.settings')->set('site_id', $site_id)->save();
    $this->config('matomo.settings')->set('url_http', 'http://www.example.com/matomo/')->save();
    $this->config('matomo.settings')->set('url_https', 'https://www.example.com/matomo/')->save();

    // Basic test if the feature works.
    $custom_vars = [
      1 => [
        'slot' => 1,
        'name' => 'Foo 1',
        'value' => 'Bar 1',
        'scope' => 'visit',
      ],
      2 => [
        'slot' => 2,
        'name' => 'Foo 2',
        'value' => 'Bar 2',
        'scope' => 'page',
      ],
      3 => [
        'slot' => 3,
        'name' => 'Foo 3',
        'value' => 'Bar 3',
        'scope' => 'page',
      ],
      4 => [
        'slot' => 4,
        'name' => 'Foo 4',
        'value' => 'Bar 4',
        'scope' => 'visit',
      ],
      5 => [
        'slot' => 5,
        'name' => 'Foo 5',
        'value' => 'Bar 5',
        'scope' => 'visit',
      ],
    ];
    $this->config('matomo.settings')->set('custom.variable', $custom_vars)->save();
    $this->drupalGet('');

    foreach ($custom_vars as $slot) {
      $this->assertRaw('_paq.push(["setCustomVariable", ' . Json::encode($slot['slot']) . ', ' . Json::encode($slot['name']) . ', ' . Json::encode($slot['value']) . ', ' . Json::encode($slot['scope']) . ']);', '[testMatomoCustomVariables]: setCustomVariable ' . $slot['slot'] . ' is shown.');
    }

    // Test whether tokens are replaced in custom variable names.
    $site_slogan = $this->randomMachineName(16);
    $this->config('system.site')->set('slogan', $site_slogan)->save();

    $custom_vars = [
      1 => [
        'slot' => 1,
        'name' => 'Name: [site:slogan]',
        'value' => 'Value: [site:slogan]',
        'scope' => 'visit',
      ],
      2 => [
        'slot' => 2,
        'name' => '',
        'value' => $this->randomMachineName(16),
        'scope' => 'page',
      ],
      3 => [
        'slot' => 3,
        'name' => $this->randomMachineName(16),
        'value' => '',
        'scope' => 'visit',
      ],
      4 => [
        'slot' => 4,
        'name' => '',
        'value' => '',
        'scope' => 'page',
      ],
      5 => [
        'slot' => 5,
        'name' => '',
        'value' => '',
        'scope' => 'visit',
      ],
    ];
    $this->config('matomo.settings')->set('custom.variable', $custom_vars)->save();
    $this->verbose('<pre>' . print_r($custom_vars, TRUE) . '</pre>');

    $this->drupalGet('');
    $this->assertRaw('_paq.push(["setCustomVariable", 1, ' . Json::encode("Name: $site_slogan") . ', ' . Json::encode("Value: $site_slogan") . ', "visit"]', '[testMatomoCustomVariables]: Tokens have been replaced in custom variable.');
    $this->assertNoRaw('_paq.push(["setCustomVariable", 2,', '[testMatomoCustomVariables]: Value with empty name is not shown.');
    $this->assertNoRaw('_paq.push(["setCustomVariable", 3,', '[testMatomoCustomVariables]: Name with empty value is not shown.');
    $this->assertNoRaw('_paq.push(["setCustomVariable", 4,', '[testMatomoCustomVariables]: Empty name and value is not shown.');
    $this->assertNoRaw('_paq.push(["setCustomVariable", 5,', '[testMatomoCustomVariables]: Empty name and value is not shown.');
  }

}
