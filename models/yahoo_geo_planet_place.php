<?php
/**
 * Model for accessing all Yahoo GeoPlanet web service calls to do with
 * Yahoo GeoPlanet Places.
 *
 * Provides custom find types for the various calls on the web service, mapping
 * familiar CakePHP methods and parameters to the HTTP request params for
 * issuing to the web service.
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class YahooGeoPlanetPlace extends YahooGeoPlanetAppModel {

  /**
   * The name of this model
   *
   * @var name
   */
  public $name ='YahooGeoPlanetPlace';

  /**
   * The custom find types available on the model
   * 
   * @var array
   */
  public $_findMethods = array(
    'places' => true,
    'place' => true,
  );

  /**
   * Finds places matching the given criteria.
   *
   * Calls the web service described at:
   * http://developer.yahoo.com/geo/geoplanet/guide/api-reference.html#api-places
   *
   * Called using Model::find('places', $options)
   *
   * $options can include the usual keys for 'conditions', 'page', 'limit' and
   * the custom 'view' key.
   *
   * Conditions can include
   * - q : String place name to search for
   * - type : Integer corresponding to a Yahoo Geo Planet place type code
   * - woeid : Either a comma separated string or an array of one or more
   * integers corresponding to Where On Earth IDs (WEOIDs)
   * - focus : either an ISO-3166-1 country code or a WOEID
   * for more information go to
   * http://developer.yahoo.com/geo/geoplanet/guide/api_docs.html#filters
   *
   * view key can have value either 'short' or 'long' (default) and determines
   * the format of the results.
   *
   * @param string $state 'before' or 'after'
   * @param array $query The query options passed to the Model::find() call
   * @param array $results The results from the call
   * @return array Either the modified query params or results depending on the
   *  value of the state parameter.
   */
  protected function _findPlaces($state, $query = array(), $results = array()) {

    if ($state == 'before') {

      $this->request['uri']['path'] = 'places';

      // Yahoo Geo Planet calls conditions filters
      $filters = $query['conditions'];

      // Remove any unsupported conditions
      $filters = array_intersect_key($filters, array_flip(array('q', 'type', 'woeid')));

      if (isset($filters['q'])) {
        $filters['q'] = urlencode($filters['q']);
        // Wrap the q param in apostrophes if contains a comma
        if (strpos($filters['q'], ',') !== false) {
          $filters['q'] = "'" . $filters['q'] . "'";
        }
        // If focus has been specified, append that to 'q' separated by a comma
        if (isset($query['conditions']['focus'])) {
          $filters['q'] .= ', ' . $query['conditions']['focus'];
        }
      }

      foreach ($filters as $filter => $values) {

        // If multiple types or woeids passed in as arrays, make them a comma
        // separated string
        if (in_array($filter, array('type', 'woeid')) && is_array($values)) {
          $filters[$filter] = implode(',', $values);
        }

        // Write filters like ".q(<search>)"
        $filters[$filter] = ".$filter($filters[$filter])";
        
      }

      // Make filters param a string
      if (count($filters) > 1) {
        $filters = '$and(' . implode(',', $filters) . ')';
      } else {
        $filters = current($filters);
      }

      // Append filters to path element of request array
      $this->request['uri']['path'] .= $filters;

      // Limit >> count
      $limit = $query['limit'];

      // 200 is max number of places the api will return
      if ($limit > 200) {
        trigger_error(__('Max limit is 200', true), E_USER_ERROR);
      }

      // Default number of results for a places search is 1!?
      if (empty($limit)) {
        $limit = 1;
      }
      $this->request['uri']['path'] .= ';count=' . $limit;

      // Page >> start
      $start = $query['page'];
      if (empty($start)) {
        $start = 1;
      }

      // API uses start, count for paging so we need to convert page to start.
      // I.e. page 3 showing 10 results should start at row (3 - 1) * 10 = 20.
      $start -= 1;
      $start *= $limit;
      $this->request['uri']['path'] .= ';start=' . $start;

      // Extra parameters
      if (isset($query['view']) && in_array($query['view'], array('short', 'long'))) {
        $this->request['uri']['query']['view'] = $query['view'];
      }

      return $query;
      
    } else {

      return $results;
      
    }
    
  }

  /**
   * Finds place matching the given woeid.
   *
   * Calls the web service described at
   * http://developer.yahoo.com/geo/geoplanet/guide/api-reference.html#api-place
   *
   * Called using Model::find('place', $options)
   *
   * $options can include the usual keys for 'conditions' and the custom 'view'
   * or 'select' key.
   *
   * Conditions can include
   * - woeid : an integer corresponding to Where On Earth IDs (WEOIDs)
   *
   * view/select keys can have value either 'short' or 'long' (default) and
   * determine the format of the results.
   *
   * @param string $state 'before' or 'after'
   * @param array $query The query options passed to the Model::find() call
   * @param array $results The results from the call
   * @return array Either the modified query params or results depending on the
   *  value of the state parameter.
   */
  protected function _findPlace($state, $query = array(), $results = array()) {

    if ($state == 'before') {

      if (!isset($query['conditions']['woeid'])) {
        return false;
      }

      $this->request['uri']['path'] = 'place/' . $query['conditions']['woeid'];

      // Extra parameters
      if (isset($query['view']) && in_array($query['view'], array('short', 'long'))) {
        $this->request['uri']['query']['view'] = $query['view'];
      }

      return $query;

    } else {

      return $results;

    }

  }

}
?>