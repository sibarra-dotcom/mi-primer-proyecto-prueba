<?php

namespace App\Controllers;

use App\Models\ComedorMenuModel;
use App\Models\ComedorEntregaModel;

class Comedor extends BaseController
{
	  public function menu()
    {
			  if ($this->request->getMethod() === 'GET')
        {
					$data['title'] = 'Registros de Comedor';
					$data['title_group'] = 'Comedor';

					return view('comedor/menu', $data);
				}

			  if ($this->request->getMethod() === 'POST')
        {

					// Prepare the model
					$Menu = new ComedorMenuModel();
					$errors = [];

					// Loop over all days in the week (we expect 7 days in the form)
					for ($i = 0; $i < 7; $i++) {
							$date = $this->request->getPost('date_' . $i);  // Retrieve the date for the day
							$menu = $this->request->getPost('menu_' . $i);  // Retrieve the menu for the day

							// Convert the date from DD-MM-YYYY to YYYY-MM-DD format
							$formattedDate = \DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');

							// Check if a record with the same date already exists
							$existingRecord = $Menu->where('fecha', $formattedDate)->first();

							// Prepare the data to insert/update
							$data = [
									'fecha' => $formattedDate,  // Use the formatted date
									'descripcion' => $menu,
							];

							if ($existingRecord) {
									// If the record exists, perform an update
									$data['id'] = $existingRecord['id'];  // Preserve the ID for the update
									$updated = $Menu->save($data);  // Save will update if the ID exists

									if (!$updated) {
										$errors[] = "Error inserting menu for date: $date";
									}
							} else {
									// If the record doesn't exist, perform an insert
									$inserted = $Menu->insert($data);

									if (!$inserted) {
											$errors[] = "Error inserting menu for date: $date";
									}
							}

					}

					if (empty($errors)) {
						return $this->response->setJSON([
							'success' => true,
							'message' => 'user found.'
						]);
					} else {
						return $this->response->setJSON([
							'success' => false,
							'message' => json_encode($errors)
						]);
					}

				}

		}

		public function lista($id = null)
    {
			if ($this->request->getMethod() === 'GET')
			{
				$Entrega = new ComedorEntregaModel;

				if($id) {
				return $this->response->setJSON($Entrega->getById($id));
				}

				return $this->response->setJSON($Entrega->orderBy('id', 'DESC')->findAll());
			}
    }

    public function index()
    {
			  if ($this->request->getMethod() === 'GET')
        {
					$data['title'] = 'Registros de Comedor';
					$data['title_group'] = 'Comedor';

					return view('comedor/index', $data);
				}
    }


}
