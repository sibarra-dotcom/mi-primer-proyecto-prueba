<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Files extends BaseController
{
	public function download1()
	{
			$relativePath = urldecode($this->request->getGet('path'));
			log_message('debug', 'Requested path: ' . $relativePath);

			$baseDir = WRITEPATH . 'storage'; 

			$fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $relativePath);

			// Log the file being requested
			log_message('debug', 'Requested file: ' . $relativePath);
			log_message('debug', 'Resolved full path: ' . $fullPath);

			if ($fullPath && strpos($fullPath, realpath($baseDir)) === 0 && file_exists($fullPath)) {
					// Check if the path is a file and not a directory
					if (is_file($fullPath)) {
							// Log successful file check
							log_message('debug', 'File found and ready for download: ' . $fullPath);
							
							// Serve the file for download
							$mimeType = mime_content_type($fullPath);
							return $this->response
									->setContentType($mimeType)
									->setBody(file_get_contents($fullPath));
					} else {
							// If it's a directory, log the error and return a message
							log_message('error', 'Requested path is a directory, not a file: ' . $fullPath);
							return redirect()->back()->with('error', 'Expected file but found a directory.');
					}
			} else {
					// Log if the file is not found or access is denied
					log_message('error', 'File not found or access denied: ' . $fullPath);
					return redirect()->back()->with('error', 'File not found or access denied.');
			}
	}

    public function download()
    {
        $relativePath = urldecode($this->request->getGet('path'));
log_message('debug', 'Requested path: ' . $relativePath);
				$baseDir = WRITEPATH . 'storage'; 

        $fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $relativePath);

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
