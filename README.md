# WebserviceNl Client

A generic client for consuming the services from [CompanyInfo - WebservicesNl](https://webview.webservices.nl/documentation/files/introduction-txt.html).

## Installation

```composer require dmt-software/webservices-client```

### Service packages

Install a service package using composer:

```composer require dmt-software/webservices-dutchbusiness```

After installation all of the service methods become available in this client.
See [Services](#services) for a complete list of supported services. 

## Usage

### Create a Client

Create a client for communication with CompanyInfo - WebservicesNl.

```php
<?php
 
use DMT\WebservicesNl\Client\Factory\ClientFactory;
 
$credentials = ['username' => '{username}', 'password' => '{password}'];
$client = ClientFactory::createClient('soap_doclit', $credentials);
```
In this case a client is returned for communication with the `soap_doclit` endpoint. 
See [Protocols](#protocols) for all supported endpoints.

### Make a service call using a request

The client uses a CommandBus to delegate a request to a handler that can process it. 
```php
<?php 
 
use DMT\WebservicesNl\Client\Client;
use DMT\WebservicesNl\DutchBusiness\Request\GetDossierV3Request;
use DMT\WebservicesNl\DutchBusiness\Response\GetDossierV3Response;

$request = new GetDossierV3Request();
$request->setDossierNumber('34221165');
 
/** @var Client $client */
/** @var GetDossierV3Response $response */
$response = $client->execute($request);
``` 
This example sends a `GetDossierV3Request`noticing to the [DutchBusiness](https://webview.webservices.nl/documentation/files/service_dutchbusiness-php.html#Dutch_Business) service 
and returns a `GetDossierV3Response`.

### Call a service method directly

Alternatively the client accepts a direct service method call with an array of arguments. This functionality is similar 
to the native SoapClient behaviour.  
> NOTE: The requests created from your call, might be erroneous without notice. This makes it hard to debug.   
```php
<?php
 
use DMT\WebservicesNl\Client\Client;
 
/** @var Client $client */
$response = $client->dutchBusinessGetDossierV3(['dossier_number' => '34221165']);
```

## Services

Goal is to support as many services as [WebservicesNl](https://webview.webservices.nl/documentation/files/interfaces/more/services-txt.html#Service_names) provides.
If the service you are looking for isn't available, feel free to leave a [feature request](https://github.com/dmt-software/webservices-client/issues). 

The services that can be installed are:

- [DutchBusiness](https://webview.webservices.nl/documentation/files/service_dutchbusiness-php.html#Dutch_Business)

## Protocols

Currently this package supports the WebservicesNl endpoint for:
 
- soap
- soap_doclit
