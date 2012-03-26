<?php

/**
 * Base class for view data store.
 *
 * Views should provide data storage and an interface between the controller and presenters.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_View_Base implements ArrayAccess, Iterator
{
	public function __construct() {}
	public function __destruct() {}
}
