<?php
/**
* Plugin Name: Event Distance custom field
* Plugin Description: Add a distance as a custom field to The Events Calendar events
*/
 
namespace MVOC\Event_Distance;
 
class Plugin {
 
  /**
   * The meta key used to store the site editor choice in regard to an event support for distance entry.
   */
  const META_DISTANCE_KEY = '_distance';
 
  /**
   * Plugin constructor.
   * The method will register the plugin instance 
   */
  public function __construct() {
     global $mvoc_event_distance;
     $mvoc_event_distance = $this;
  }
 

  /**
   * Prints the HTML fragment required to display an edit box in the Events edit screen for the distance.
   *
   * @param int $event_id The post ID of the event currently being edited.
   */
  public function print_backend_controls( $event_id ) {
     $html_template = '<tr>' .
                      '<td class="tribe_sectionheader" colspan="2">' .
                      '<h4>MVOC Custom Details</h4>' .
                      '</td>' .
                      '</tr>' .     
                      '<tr>' .
                      '<td>%s</td>' .
                      '<td><input type="number" name="%s" value="%s"></td>' .
                      '</tr>';
 
     printf(
        $html_template,
        esc_html( 'Distance (miles):' ),
        self::META_DISTANCE_KEY,
        get_post_meta( $event_id, self::META_DISTANCE_KEY, true )
     );
  }
 
  /**
   * Handles the value of the distance editbox, saving it as post meta data
   *
   * @param int $event_id The post ID of the event currently being saved.
   */
  public function save_meta( $event_id ) {
     $distance = tribe_get_request_var( self::META_DISTANCE_KEY, false );
     if ( $distance ) {
        update_post_meta( $event_id, self::META_DISTANCE_KEY, $distance );
     } else {
        delete_post_meta( $event_id, self::META_DISTANCE_KEY );
     }
  }
 
  /**
   * Hooks the filters and actions required for the plugin to work.
   */
  public function hook() {
     // Admin section, this does not take care of the block-editor. That's left as an exercise to the reader.
     add_action( 'tribe_events_cost_table', [ $this, 'print_backend_controls' ] );
     add_action( 'save_post_tribe_events', [ $this, 'save_meta' ] );
  }
}
 
// Instantiate the plugin.
$plugin = new Plugin;
 
// Hook the plugin to the filters and actions required by it to work.
$plugin->hook();
