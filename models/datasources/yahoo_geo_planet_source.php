<?php

// Import the rest data source from the rest plugin
App::import('Datasource', 'Rest.RestSource');

/**
 * CakePHP DataSource for accessing the Yahoo GeoPlanet API.
 *
 * Extends the Rest DataSource from the Rest plugin
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class YahooGeoPlanetSource extends RestSource {

  /**
   * Overloads the RestSource::request() method to add Yahoo GeoPlanet API
   * specific elements to the request property of the passed model before
   * sending it off to the RestSource::request() method that actually issues the
   * request and decodes the response.
   * 
   * @param AppModel $model The model the call was made on. Expects the model
   * object to have a request property in the form of HttpSocket::request
   * @return mixed
   */
  public function request(&$model) {

    if (!isset($model->request['uri']['host'])) {
      $model->request['uri']['host'] = 'where.yahooapis.com';
    }

    if (!isset($model->request['uri']['query']['appid'])) {
      $model->request['uri']['query']['appid'] = $this->config['appid'];
    }

    if (!isset($model->request['uri']['query']['format'])) {
      $model->request['uri']['query']['format'] = 'json';
    }

    // Prefixes the path element of the request with 'v1/' if a version is not
    // already present.
    if (isset($model->request['uri']['path']) && !preg_match('/^v\d+/', $model->request['uri']['path'])) {
      $model->request['uri']['path'] = 'v1/' . $model->request['uri']['path'];
    }

    $response = parent::request($model);

    return $response;

  }

}
?>