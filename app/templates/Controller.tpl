<?php
namespace Controllers\Admin;

use AdminController;
use View;
use Sentry;
use Redirect;
use Input;
use Validator;
use Grid;
use Category;
use Image;
use Config;
use Response;
use Sizes;
use App;
use Request;
use DataGrid;
use DB;

class className extends AdminController {

	protected $permission_prefix = '#permission_prefix#';

	/**
	 * Listing page
	 */
	public function getIndex() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.view')) {
         return App::abort('403');
      }
	}


	/**
	 * Create new record
	 */
	public function getCreate() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.create')) {
         return App::abort('403');
      }

      return View::make('backend/products/create');
	}

	/**
	 * Post create new record
	 */
	public function postCreate() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.create')) {
         return App::abort('403');
      }
	}


	/**
	 * Edit record
	 */
	public function getEdit() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.edit')) {
         return App::abort('403');
      }

      return View::make('backend/products/edit');
	}


	/**
	 * Post edit record
	 */
	public function postEdit() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.edit')) {
         return App::abort('403');
      }
	}


	/**
	 * Delete record
	 */
	public function getDelete() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.edit')) {
         return App::abort('403');
      }
	}


	/**
	 * Active record
	 */
	public function getActive() {
		// Check permission
      //
      if (!Sentry::getUser()->hasAccess($this->permission_prefix . '.edit')) {
         return App::abort('403');
      }

      $json = array(
      	'code' => 0,
      	'message' => 'Có lỗi'
      );

      return Response::json($json);
	}
}