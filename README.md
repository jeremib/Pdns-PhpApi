# Pdns-PhpApi
===========

PHP RESTful API for PowerDNS

## Usage

The following shows how to call the API.  Pdns-PhpApi adheres to the RESTful practice of ustilizing POST, PUT, DELETE and GET.

The default username/password is admin/foo.

### Domain Actions

The following calls are for adding/editing/deleting domain information.  Primary key is the domain name.

#### List Domains

Returns all the domains, but does not include their records.

 ```GET /domain/ HTTP/1.1```

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

#### Update Domain Record

Updates a domain record

 ```POST /domain HTTP/1.1```

 Parameters
 - domain : domain name of the new record [Optional] [Default null]
 - type : type of the new record, either MASTER or SLAVE [Optional] [Default MASTER]
 - master : master record if type is SLAVE [Optional] [Default null]

#### Delete Domain Info

Deletes the specified domain and all associated records

 ```DELETE /domain/<domain.com> HTTP/1.1```

### Record Actions

The following calls are for add/editing/deleting/listing records for the domain.  Primary key is the record id.


#### Get Record Info

Returns the record information for the specified name, for example ns1.mydomain.com.  Key can also be the id
of the record you want information for.

 ```GET /record/<key> HTTP/1.1```

#### Get Record Info For Domain and Type

Returns the record information for the specified domain and type.  For example to list all NS records for a domain

 ```GET /record/<domain>/<type> HTTP/1.1```

#### Create Record

Creates a record for a domain

 ```POST /record HTTP/1.1```

 Parameters
 - domain : domain name for the record [Required]
 - name : name of the record [Required]
 - type : type of the record [Required]
 - content : content of the record [Required]
 - ttl : time to live [Optional] [Default 3600]
 - prio : priority of the record [Optional]

#### Update Record

Updates a record for the id specified.

 ```PUT /record/<id> HTTP/1.1```

 Parameters
 - name : name of the record [Required]
 - type : type of the record [Required]
 - content : content of the record [Required]
 - ttl : time to live [Optional] [Default 3600]
 - prio : priority of the record [Optional]

#### Delete Info

Deletes the specified record

 ```DELETE /record/<id> HTTP/1.1```