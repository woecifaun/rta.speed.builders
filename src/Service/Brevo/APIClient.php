<?php

namespace App\Service\Brevo;

use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use GuzzleHttp;

/**
 * Instance will be in charge of communicating with Brevo API
 * More info at https://github.com/getbrevo/brevo-php/blob/main/docs/Api/ContactsApi.md#createContact
 */
class APIClient
{
    public const MOVIE_NEWSLETTER_SUBSCRIBER = 6;

    private ContactsApi $apiInstance;

    function __construct(private $apiKey)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $config = Configuration::getDefaultConfiguration()->setApiKey('partner-key', $apiKey);

        $this->apiInstance = new ContactsApi(
            new GuzzleHttp\Client(),
            $config
        );
    }

    public function newContact(array $contact, int $listId = null)
    {
        $newContact = new CreateContact();
        $newContact
            ->setEmail($contact['email'])
            ->setAttributes(['NAME' => $contact['name']]);

        if ($listId) {
            $newContact->setListIds((array) $listId);
        }

        try {
            $result = $this->apiInstance->createContact($newContact);
            // print_r($result);
        } catch (Exception $e) {
            throw new Exception('Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL);
        }
    }
}
