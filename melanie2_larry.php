<?php
/**
 * Plugin Melanie2 Larry
 *
 * Apply plugins css for melanie2_larry skin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

class melanie2_larry extends rcube_plugin
{
  /**
   * @var string
   */
  public $task = '.*';
  /**
   * Skin name
   * @var string
   */
  const SKIN_NAME = 'melanie2_larry';
  /**
   * Css folder
   * @var string
   */
  const CSS_FOLDER = 'css/';
  /**
   * Global css file for Roundcube
   * @var string
   */
  const APP_CSS = 'styles.css';
  /**
   * Map between plugin and css file
   * @var array
   */
  public static $plugins_css_map = [
    'jqueryui' => 'jqueryui.css'
  ];

  public static $tasks_css_map = [
    'mail' => 'mail.css',
    'addressbook' => 'addressbook.css',
    'settings' => 'settings.css',
    'calendar' => 'calendar.css',
  ];

  /**
   * Initialisation du plugin
   * @see rcube_plugin::init()
   */
  function init()
  {
    // default startup routine
    $this->add_hook('startup', array($this, 'startup'));
  }

  /**
   * Startup hook
   */
  public function startup($args) {
    if ($this->ui_initialized) {
      return;
    }
    $rc = rcmail::get_instance();
    $plugins = $rc->config->get('plugins');
    $skin = $rc->config->get('skin');
    // Check if the user use melanie2_larry skin
    if ($skin == self::SKIN_NAME && !$rc->output->get_env('mobile')) {
      // For each plugin, add the associated css file
      foreach(self::$plugins_css_map as $plugin_name => $css_file) {
        if (in_array($plugin_name, $plugins)) {
          $this->include_stylesheet(self::CSS_FOLDER.$css_file);
        }
      }
      // Add the associated task css file
      if (isset(self::$tasks_css_map[$rc->task])) {
        $this->include_stylesheet(self::CSS_FOLDER.self::$tasks_css_map[$rc->task]);
      }
      // Load other custom css files
      $this->include_stylesheet(self::CSS_FOLDER.self::$plugins_css_map['jqueryui']);
      $this->include_stylesheet(self::CSS_FOLDER.self::APP_CSS);
    }
    $this->ui_initialized = true;
  }
}