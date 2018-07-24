# WebserviceNl Client

A generic client for consuming the services from CompanyInfo - WebservicesNl.

## Installation

```composer require dmt-software/webservices-client```

### Services

```composer require dmt-software/webservices-dutchbusiness```

## Usage

Create a client for communication with CompanyInfo - WebservicesNl.

```php
<?php
 
use DMT\WebservicesNl\Client\Factory\ClientFactory;
 
$credentials = ['username' => '{username}', 'password' => '{password}'];
$client = ClientFactory::createClient('soap_doclit', $credentials);
```
In this case a client is returned for communication with the `soap_doclit` endpoint. 
See [Protocols](#protocols) for all supported endpoints.

### Protocols

Currently this package supports the WebservicesNl endpoints for:
 
- soap
- soap_doclit
