<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EmailQueueModel;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\CURLRequest; 

class SendQueuedEmails extends BaseCommand
{
    protected $group       = 'email';
    protected $name        = 'send:queued-emails';
    protected $description = 'Send all pending emails from the queue.';

    public function run(array $params)
    {
				$emailModel = new EmailQueueModel();
				$emails = $emailModel->where('status', 'pending')->findAll();

				$emailService = \Config\Services::email();
				$httpClient   = \Config\Services::curlrequest();

				$whatsappToken = 'EAAHgRgpoW0oBO0b5unG5ZA0w6xJutOSRPZCcgonKBijEcZCapfTF88r5urY2HJgiM9EaKH7cVSZBrZBHTzxry8YrngXOZAx9CigommtrJ2xwGgVpmp7dpsVOpz3YBO9SVACBDna5eZCZAB8HjkjtayCUlkiHZAwZAdJFOaeGCybzrZCgPGOL1yWc2Ide1OFURGeZCGhhv4nzayVZCZB2tlgfXJwrIzrZCPe9JiCVxqOhErTZBK9t';
				$whatsappPhoneNumberId = '570841519450120';

        foreach ($emails as $email) {
						$recipients = strpos($email['recipient'], ',') !== false 
						? array_map('trim', explode(',', $email['recipient'])) 
						: [trim($email['recipient'])];

						// Send Email
						$emailService->setFrom('c.lara@gibanibb.com', 'C. Lara');
						$emailService->setTo($recipients);
						$emailService->setSubject($email['subject']);
						$emailService->setMessage($email['body']);

						if ($emailService->send()) {
								$emailModel->update($email['id'], ['status' => 'sent']);
								CLI::write("Email sent to: " . implode(', ', $recipients), 'green');

								foreach ($recipients as $recipient) {
										$this->sendWhatsAppMessage($httpClient, $whatsappToken, $whatsappPhoneNumberId, $recipient, $email['body']);
								}
						} else {
								$emailModel->update($email['id'], ['status' => 'failed']);
								CLI::error("Failed to send email to: " . implode(', ', $recipients));
						}
        }
    }

    private function sendWhatsAppMessage($httpClient, $token, $phoneNumberId, $recipient, $message)
    {
        // $recipientWhatsApp = $this->convertToWhatsAppNumber($recipient); 
        $recipientWhatsApp = "523312197369";

        if (!$recipientWhatsApp) {
            CLI::error("No valid WhatsApp number for recipient: $recipient");
            return;
        }

        $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type"    => "individual",
            "to"                => $recipientWhatsApp,
            "type"              => "text",
            "text"              => [
							"body" => "Notificacion Mantenimiento : \n $message"
						]
        ];

        $response = $httpClient->request('POST', $url, [
            'headers' => [
                'Authorization' => "Bearer $token",
                'Content-Type'  => 'application/json'
            ],
            'json' => $payload
        ]);

        if ($response->getStatusCode() == 200) {
            CLI::write("WhatsApp message sent to: $recipientWhatsApp", 'green');
        } else {
            CLI::error("Failed to send WhatsApp message: " . $response->getBody());
        }
    }

    private function convertToWhatsAppNumber($email)
    {
        // This function should map email to a WhatsApp number
        // Example: Fetch from database based on user profile
				// obtener el numero de whatsapp con email de usuario
        $mapping = [
            'user@test.com' => '521234567890', // Example mapping
        ];

        return $mapping[$email] ?? null; // Return WhatsApp number or null if not found
    }
}

