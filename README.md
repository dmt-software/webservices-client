# WebserviceNL Client

A generic client for consuming the services from CompanyInfo - WebservicesNL.
Currently supports SOAP (document-literal and rpc-encoded)

## Usage

```php
<?php
 
use DMT\WebservicesNl\Client\Client;
use DMT\WebservicesNl\Client\Factory\Soap\ClientBuilder;
use DMT\WebservicesNl\DutchBusiness\Request\GetDossierV3Request;

$client = ClientBuilder::create()
    ->setAuthorization(['username' => '__USERNAME__', 'password' => '__PASSWORD__'])
    ->setServiceEndpoint('https://ws1.webservices.nl/soap_doclit/')
    ->build();
 
$request = new GetDossierV3Request();
$request->setDossierNumber('34221165');
 
/** @var Client $client */
$response = $client->execute($request);
```
