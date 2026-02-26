<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    // protected $useSoftDeletes   = true;  // If true, then any delete() method calls will set deleted_at in the database
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rol_id', 'email', 'password', 'name', 'last_name', 'phone', 'address', 'signature', 'picture', 'pin', 'confirmar_aviso'];

    protected bool $allowEmptyInserts = false; //  The default value is false, meaning that if you try to insert empty data, DataException with “There is no data to insert.” will raise.
    protected bool $updateOnlyChanged = true; // Setting this property to false will ensure that all allowed fields of an Entity are submitted to the database and updated at any time.

    protected array $casts = [];
    protected array $castHandlers = [];

    // You only need to include created_at and updated_at in $allowedFields if you want to set these fields manually. 
    // For example, if you’re inserting a record with a specific created_at date instead of letting CodeIgniter set it for you, you’d include them in $allowedFields.

    // Dates
    protected $useTimestamps = true; //  If true, will set the current time in the format specified by $dateFormat. 
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

		public function getAllPersonal($id = null)
		{
			$builder = $this->select('
				users.id, 
				users.created_at, 
				users.name, 
				users.last_name, 
				users_operarios.turno,
				users_operarios.puesto,
				users_operarios.empleadoId
				')
				->join('users_operarios', 'users_operarios.userId = users.id', 'left')
				->where('users.rol_id', 11);

				if($id) {
					$builder->where('users.id', $id)
					->orderBy('users.id', 'DESC');
				}

				$builder->orderBy('users.id', 'DESC');

				
				if($id) {
					return $builder->get()->getRowArray();
				} else {
					return $builder->get()->getResultArray();
				}
		}

		public function getById($reporteId)
    {
			$builder = $this->select('

					reportes.*,
					users.name, 
					users.last_name, 
			')
			->join('users', 'users.id = reportes.produccionId', 'left') 
			->where('reportes.id', $reporteId);

			return $builder->get()->getRowArray();
		}



    public function getByPIN($pin)
		{
				return $this->select('
										users.id AS user_id,
										users.name,
										users.last_name,
										users.pin,
										roles.rol
										')
										->join('roles', 'roles.id = users.rol_id')
										->where('users.pin', $pin)
										->first();
		}


    public function getUserByEmail1($email)
    {
        return $this->select('users.*, roles.rol')
                    ->join('roles', 'roles.id = users.rol_id')
                    ->where('users.email', $email)
                    ->first();
    }

		public function getUserByEmail($email)
		{
				return $this->select(
								'users.*, 
								roles.rol,
								users_operarios.empleadoId,
								users_operarios.turno,
								users_operarios.puesto'
						)
						->join('roles', 'roles.id = users.rol_id')
						->join('users_operarios',	'users_operarios.userId = users.id', 'left')
						->where('users.email', $email)
						->first();
		}

    public function getUserByPIN($email, $pin)
    {
        return $this->select('
												users.*,
												roles.rol,
												users_operarios.turno,
												users_operarios.puesto
												')
                    ->join('roles', 'roles.id = users.rol_id')
										->join('users_operarios',	'users_operarios.userId = users.id', 'left')
                    ->where(['users.pin' => $pin, 'users.email' => $email])
                    ->first();
    }

		public function getUserByPINId($id, $pin)
    {
        return $this->select('
												users.*,
												roles.rol,
												users_operarios.turno,
												users_operarios.puesto
												')
                    ->join('roles', 'roles.id = users.rol_id')
										->join('users_operarios',	'users_operarios.userId = users.id', 'left')
                    ->where(['users.pin' => $pin, 'users.id' => $id])
                    ->first();
    }

    public function getRole($userId)
    {
        $user = $this->find($userId); // Fetch the user by ID
        if ($user) {
            $db = \Config\Database::connect();
            $role = $db->table('roles')
                ->where('id', $user['rol_id']) // Find role by foreign key
                ->get()
                ->getRow();
            return $role; // Return the role
        }
        return null; // User or role not found
    }


    public function getUsersWithRoles()
    {
        return $this->select('users.*, roles.rol')
                    ->join('roles', 'roles.id = users.rol_id')
										->orderBy('users.id', 'DESC')
                    ->findAll();
    }

}
