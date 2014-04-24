# Pdns-PhpApi
===========

PHP RESTful API for PowerDNS

## Usage

The following shows how to call the API.  Pdns-PhpApi adheres to the RESTful practice of ustilizing POST, PUT, DELETE and GET.

### Domain Actions

The following calls are for adding/editing/deleting domain information

#### Get Domain Info

Returns all the domain information for the specified domain, including records associated with that domain.

 ```GET /domain/<domain.com> HTTP/1.1```
 
#### Create Domain Record

Creates a domain record

 ```POST /domain HTTP/1.1```
 
 Parameters
 - domain : domain name of the new record [Required]
 - type : type of the new record, either MASTER or SLAVE [Optional] [Default MASTER]
 - master : master record if type is SLAVE [Optional] [Default null]
