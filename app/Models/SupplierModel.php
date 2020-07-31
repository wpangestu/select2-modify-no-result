<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
	protected $table      = 'supplier';
	protected $primaryKey = 'id';

	protected $allowedFields = ['first_name', 'email','phone','company'];
	protected $returnType     = 'object';
	protected $useSoftDeletes = false;

}