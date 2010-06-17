<?php
/**
 * Rudimentary code for the purpose of demonstrating plugin functionality and
 * model API through which the plugin functionality is intended to be accessed.
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class YahooGeoPlanetPlacesController extends YahooGeoPlanetAppController {

  /**
   * The name of this controller
   *
   * @var string
   */
  public $name = 'YahooGeoPlanetPlaces';

  /**
   * Demo action for paginating through the places returned from the web service
   * matching a search term.
   */
  public function places() {

    // If search form submitted, redirect to same action with the search term in
    // the url
    if (!empty($this->data['YahooGeoPlanetPlace'])) {
      $this->redirect($this->data['YahooGeoPlanetPlace']);
    }

    // If there is a search term in the url, add it to Controller::data so it's
    // repopulated in the form, and add it to the Controller::paginate
    // conditions before actually fetching the page of the result set from the
    // web service.
    if (!empty($this->passedArgs)) {
      $this->data['YahooGeoPlanetPlace'] = $this->passedArgs;
      $this->paginate['YahooGeoPlanetPlace'] = array(
        'places',
        'conditions' => $this->passedArgs,
      );
      $this->set('results', $this->paginate('YahooGeoPlanetPlace'));
    }

  }
  
}
?>