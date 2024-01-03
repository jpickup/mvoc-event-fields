<?php
/**
 * Plugin Name: Event custom fields for MVOC events
 * Plugin Description: Add custom fields to The Events Calendar events
 */

namespace MVOC\Event_Fields;

class Plugin
{
   /**
    * The meta keys used to store the custom fields
    */
   const MVOC_DISTANCE_KEY = '_mvoc_distance';
   const MVOC_BOF_ID_KEY = '_mvoc_bof_id';
   const MVOC_LATITUDE_KEY = '_mvoc_latitude';
   const MVOC_LONGITUDE_KEY = '_mvoc_longitude';
   const MVOC_W3W_KEY = '_mvoc_w3w';
   const MVOC_ONLINE_ENTRY_KEY = '_mvoc_online_entry';

   /**
    * Plugin constructor.
    * The method will register the plugin instance 
    */
   public function __construct()
   {
      global $mvoc_event_fields;
      $mvoc_event_fields = $this;
   }

   /**
    * Prints the HTML fragment required to display edit boxes in the Events edit screen for the custom fields.
    *
    * @param int $event_id The post ID of the event currently being edited.
    */
   public function print_backend_controls($event_id)
   {
      $html_header = '<tr>' .
         '<td class="tribe_sectionheader" colspan="2">' .
         '<h4>MVOC Custom Details</h4>' .
         '</td>' .
         '</tr>';
      $html_text_template = '<tr>' .
         '<td>%s</td>' .
         '<td><input type="text" name="%s" value="%s"></td>' .
         '</tr>';

      $html_numeric_template = '<tr>' .
         '<td>%s</td>' .
         '<td><input type="number" step="any" name="%s" value="%s"></td>' .
         '</tr>';

      printf($html_header);

      printf(
         $html_numeric_template,
         esc_html('Distance (miles):'),
         self::MVOC_DISTANCE_KEY,
         get_post_meta($event_id, self::MVOC_DISTANCE_KEY, true)
      );
      printf(
         $html_numeric_template,
         esc_html('BOF ID:'),
         self::MVOC_BOF_ID_KEY,
         get_post_meta($event_id, self::MVOC_BOF_ID_KEY, true)
      );
      printf(
         $html_numeric_template,
         esc_html('Latitude:'),
         self::MVOC_LATITUDE_KEY,
         get_post_meta($event_id, self::MVOC_LATITUDE_KEY, true)
      );
      printf(
         $html_numeric_template,
         esc_html('Longitude:'),
         self::MVOC_LONGITUDE_KEY,
         get_post_meta($event_id, self::MVOC_LONGITUDE_KEY, true)
      );
      printf(
         $html_text_template,
         esc_html('Online entry URL:'),
         self::MVOC_ONLINE_ENTRY_KEY,
         get_post_meta($event_id, self::MVOC_ONLINE_ENTRY_KEY, true)
      );
      printf(
         $html_text_template,
         esc_html('What3Words:'),
         self::MVOC_W3W_KEY,
         get_post_meta($event_id, self::MVOC_W3W_KEY, true)
      );
   }

   private function save_single_meta($event_id, $MVOC_key) {
      $MVOC_value = tribe_get_request_var($MVOC_key, false);
      if ($MVOC_value) {
         update_post_meta($event_id, $MVOC_key, $MVOC_value);
      } else {
         delete_post_meta($event_id, $MVOC_key);
      }      
   }

   /**
    * Handles the value of the distance editbox, saving it as post meta data
    *
    * @param int $event_id The post ID of the event currently being saved.
    */
   public function save_meta($event_id)
   {
      self::save_single_meta($event_id, self::MVOC_DISTANCE_KEY);
      self::save_single_meta($event_id, self::MVOC_BOF_ID_KEY);
      self::save_single_meta($event_id, self::MVOC_LATITUDE_KEY);
      self::save_single_meta($event_id, self::MVOC_LONGITUDE_KEY);
      self::save_single_meta($event_id, self::MVOC_ONLINE_ENTRY_KEY);
      self::save_single_meta($event_id, self::MVOC_W3W_KEY);
   }

   /**
    * Hooks the filters and actions required for the plugin to work.
    */
   public function hook()
   {
      // Admin section, this does not take care of the block-editor. That's left as an exercise to the reader.
      add_action('tribe_events_cost_table', [$this, 'print_backend_controls']);
      add_action('save_post_tribe_events', [$this, 'save_meta']);
   }
}

// Instantiate the plugin.
$plugin = new Plugin;

// Hook the plugin to the filters and actions required by it to work.
$plugin->hook();
