<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Files extends BaseController
{

    public function download()
    {
        $relativePath = urldecode($this->request->getGet('path'));

				$baseDir = WRITEPATH . 'storage'; 

        $fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $relativePath);
        // var_dump($fullPath);

        // if (!is_readable($fullPath)) {
        //     throw new \Exception("File not readable: $fullPath");
        // }

        // exit;

        if ($fullPath && strpos($fullPath, realpath($baseDir)) === 0 && file_exists($fullPath)) {
            // Serve the file for download
            // return $this->response->download($fullPath, null);

            $mimeType = mime_content_type($fullPath);
            return $this->response
                ->setContentType($mimeType)
                ->setBody(file_get_contents($fullPath));

        } else {
            return redirect()->back()->with('error', 'File not found or access denied.');
        }
    }

		public function image($relativePath ="dephzan.jpg")
		{
				$baseDir = WRITEPATH . 'storage'; // Base directory: /writable/storage

				// Resolve the absolute path
				$fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $relativePath);

						//     var_dump($fullPath);

						// if (!is_readable($fullPath)) {
						//     throw new \Exception("File not readable: $fullPath");
						// }

						// exit;

				// Debugging logs
				if (!file_exists($fullPath)) {
						return $this->response->setStatusCode(404)->setBody("Error: File does not exist - $fullPath");
				}
				if (!is_file($fullPath)) {
						return $this->response->setStatusCode(400)->setBody("Error: Not a valid file (Is it a directory?) - $fullPath");
				}

				if ($fullPath && strpos($fullPath, realpath($baseDir)) === 0 && file_exists($fullPath)) {
						// Serve the file for download
						// return $this->response->download($fullPath, null);

						$mimeType = mime_content_type($fullPath);
						return $this->response
								->setContentType($mimeType)
								->setBody(file_get_contents($fullPath));

				} else {
						return redirect()->back()->with('error', 'File not found or access denied.');
				}
		}


}
