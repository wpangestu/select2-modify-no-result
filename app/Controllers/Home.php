<?php namespace App\Controllers;

use App\Models\SupplierModel;

class Home extends BaseController
{
	public function index()
	{
		$data = new SupplierModel();
		$supplier = $data->findAll();
		return view('home',compact('supplier'));
	}

	public function json()
	{
		$request = \Config\Services::request();

		$params = $request->getVar('q');
		$page = $request->getVar('page');

		$supplierModel = new SupplierModel();

		if(!empty($params)){
			$supplier = $supplierModel->select('id, first_name as text')
									->like('first_name', $params, 'both')
									->orderBy('first_name','ASC');
		}else{
			$supplier = $supplierModel->select('id, first_name as text')
									->orderBy('first_name','ASC');
		}

		$data = [
			"count_filtered" => $supplier->countAllResults(false),
			"page" => $page,
			"results" => $supplier->paginate(10),
		];

		echo json_encode($data);
	}

	public function quick_save()
	{
		$request = \Config\Services::request();
		$db = \Config\Database::connect();

		$name = $request->getVar('name');
		$el = $request->getVar('elemen');

		$data = [
			"first_name" => $name,
			"email"	=> $request->getVar('email'),
			"phone" => $request->getVar('phone'),
			"company" => $request->getVar('company')
		];

		$supplierModel = new SupplierModel();

		$insert = $supplierModel->insert($data);

		if($insert){
			$status = true;
			$data = array(
				"id" => $db->insertID(),
				"name" => $name
			);
		}else{
			$status = false;
			$data = array();
		}

		$result = [
			"status" => $status,
			"data" => $data,
			"elemen" => $el
		];

		echo json_encode($result);
	}

	//--------------------------------------------------------------------

}
