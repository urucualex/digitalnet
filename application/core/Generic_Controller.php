<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Generic_Controller extends CI_Controller {

	public $_item_name = ''; // used for view names -> $item.'.php' (edit item view), $item.'s.php' (items list view), item data will be loaded in $this->data[_item_name]
	public $_model = ''; // main model used by the controller, loaded on construct

	public $data = []; // data used in view

	public $_success_result = [
		'status' => 'ok',
		'message' => 'Action successfull!'
	];

	public $_error_result = [
		'status' => 'error',
		'message' => 'Server error!'
	];

	public $_result = [];

	public function __construct() {
		parent::__construct();

		if (!empty($this->_model)) {
			$this->load->model($this->_model);
		}

		$this->data['_current_controller'] = $this->_item_name;
		$this->_result = $this->_error_result;
	}

	// default action
	public function index() {
		$this->items();
	}

	// load view with data for all items
	public function items() {
		$model_name = $this->_model;
		$item_name = $this->_item_name;
		$items = $this->$model_name->read_all();

		if ($this->input->is_ajax_request()) {
			echo json_encode($items);
		}
		else {
			$this->data[$item_name.'s'] = $items;
			$this->load->view($this->_item_name.'s', $this->data);
		}

		return TRUE;
	}

	// load view with data for one item
	public function item($Id = NULL) {
		$model_name = $this->_model;
		$item_name = $this->_item_name;

		if ($Id > 0) {
			$this->data[$item_name] = $this->$model_name->read($Id);
			if (!$this->data[$item_name]) {
				exit;
			}
		} else {
			$this->data[$item_name] = [];
		}
debug('Item data', $this->data);
		$this->load->view($this->_item_name, $this->data);
	}

	// update/create item
	public function update($Id = NULL) {
		$data = $this->input->post(NULL, TRUE);
debug('POST: ', $data);

		if (!empty($data))
		{
			$model_name = $this->_model;
			$item_name = $this->_item_name;

			$id = $data['id'];
			unset($data['id']);

			if ($id > 0)
			{
				if ($this->$model_name->update($id, $data))
				{
					$this->_result = array(
							  'status' => 'ok'
							, 'message' => __($item_name.' updated!')
							, 'id' => $id
						);
				}
				else
				{
					$this->_result['message'] = get_error(__('Error updating '.$item_name));
				}
			}
			else
			{
				if ($new_id = $this->$model_name->create($data))
				{
					$this->_result = array(
							  'status' => 'ok'
							, 'message' => __($item_name.' created!')
							, 'id' => $new_id
						);
					$id = $new_id;
				}
				else
				{
					$this->_result['message'] = get_error(__('Error creating '.$item_name));
				}
			}
		}

		if ($this->input->is_ajax_request())
		{
			echo json_encode($this->_result);
		}
		else
		{
			$this->load->helper('url');
			redirect('/'.$item_name.'s/'.$id);
		}

		return ($this->_result['status'] == 'ok');
	}


	public function delete($Id = NULL)
	{
		$model_name = $this->_model;
		$item_name = $this->_item_name;

		if ( $this->$model_name->delete($Id) ) {
			$this->_result = array(
					'status' => 'ok',
					'message' => __($item_name.' deleted')
				);
		}
		else
		{
			$this->_result['message'] = get_error(__('Error deleting '.$item_name.'!'));
		}

		if ($this->input->is_ajax_request())
		{
			echo json_encode($this->_result);
		}
		else
		{
			$this->load->helper('url');
			redirect('/'.$item_name.'s');
		}

		return ($this->_result['status'] == 'ok');
	}

	public function php_test() {
		phpinfo();
	}
}
