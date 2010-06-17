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
echo $this->Form->create('YahooGeoPlanetPlace', array('url' => array('action' => 'places')));
echo $this->Form->input('q', array('label' => 'Search'));
echo $this->Form->end();
if (isset($results)) {
  $this->Paginator->options(array('url' => $this->passedArgs));
  echo $this->Paginator->numbers();
	echo $this->Paginator->prev('< Previous ', null, null, array('class' => 'disabled'));
	echo $this->Paginator->next(' Next >', null, null, array('class' => 'disabled'));
  echo $this->Paginator->counter(array(
    'format' => 'Page %page% of %pages%, showing records %start% to %end% out of %count%'
  ));
  pr($results);
}

?>
