<?php
/**
 * Yahoo GeoPlanet Plugin App Controller
 *
 * CakePHP's Controller::paginate() method makes 2 calls to the model object
 * being paginated, the first to get the total number of results in the set,
 * paginateCount(), (normally a count(*) with conditions but no limit clause),
 * and then paginate() to get the current page's results with limit clause.
 *
 * The paginate() method in this controller and the paginateCount() and
 * paginate() methods in the model allow the use of CakePHP's pagination
 * functionality and helper to paginate through result sets from data sources
 * that include the current page's results and the total number of results in
 * the whole set in one call, such as those commonly returned from web services.
 *
 * The approach involves setting a property in the model being paginated with
 * the pagination information from the Controller::paginate property along with
 * any paging params from the URL. This is so the pagination params are *all*
 * available in the Model::paginateCount() method. Some relevant params are
 * passed in, but ones like show/limit and page are not. Now
 * Model::paginateCount() has all the pagination params, it can make the call to
 * the web service, store the results in another property of the model and
 * return the total number of results value included in the response from the
 * web service. Controller::paginate() then calls Model::paginate() which simply
 * returns the results it stored in the model property in the previous call to
 * Model::paginateCount().
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class YahooGeoPlanetAppController extends AppController {

  /**
   * Overrides CakePHP's default paging settings, optional.
   *
   * @var array
   */
  public $paginate = array(
    'page' => 1,
    'limit' => 10,
  );

  /**
   * Overrides Controller::paginate() to set paging options in the
   * Model::paginate property so they available in the Model::paginateCount()
   * method.
   *
   * @param mixed $object
   * @param mixed $scope
   * @param mixed $whitelist
   * @return array The result set
   */
  public function paginate($object = null, $scope = array(), $whitelist = array()) {
    if (isset($this->passedArgs['page'])) {
      $this->paginate['page'] = $this->passedArgs['page'];
    }
    if (isset($this->passedArgs['show'])) {
      $this->paginate['limit'] = $this->passedArgs['show'];
    }
    $options = $this->paginate;
    if (isset($options[$object])) {
      $options = array_merge($options, $options[$object]);
      unset($options[$object]);
    }
    $this->$object->paginate = $this->paginate[$object] = $options;
    return parent::paginate($object = null, $scope = array(), $whitelist = array());
  }
  
}
?>