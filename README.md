# KEY VALUE STORE

This is a RESTful Api based application to stroe key and value. The purpose of the API is to store value with respect to a key and later fetch these values by keys.


### Prerequisites

* PHP >= 7.2.0
* Composer

### Installing

To Do:

* Clone this repository.
* ```cd``` into the cloned repository.
* Copy ```.env.example``` to ```.env```.
* Change necessary values.
* Run ```composer install```.
* Run ```php artisan key:generate```.
* Run ```php artisan migrate --seed```.
* Open your browser & hit to ```http://127.0.0.1:PORT_NUMBER```.


### Add key values

``` POST http://127.0.0.1:YOUR_PORT/api/v1/values```

Request Body
```
{
	"key1": "Bali, Indonesia", 
	"key2": "Bora Bora",
	"key3": "maldives island"
}
```

Response
```
{
    "is_success": true,
    "message": "Operation completed successfully"
}
```


### Get all key values and reset ttl for those values

``` GET http://127.0.0.1:YOUR_PORT/api/v1/values```

Response
```
{
    "is_success": true,
    "key_values": [
        {
            "key": "key1",
            "value": "Bali, Indonesia"
        },
        {
            "key": "key2",
            "value": "Bora Bora"
        },
        {
            "key": "key3",
            "value": "maldives island"
        }
    ]
}
```


### Get one or more specific values from the store and also reset the TTL of those keys.

``` GET http://127.0.0.1:YOUR_PORT/api/v1/values?keys=key1,key2```

Response
```
{
    "is_success": true,
    "key_values": [
        {
            "key": "key1",
            "value": "Bali, Indonesia"
        },
        {
            "key": "key2",
            "value": "Bora Bora"
        }
    ]
}
```


### Update a value in the store and also reset the TTL.

``` PATCH http://127.0.0.1:YOUR_PORT/api/v1/values```

Request Body
```
{
	"key1": "Kashmir", 
	"key2": "Sikim",
	"key4": "test"
}
```

Response
```
{
    "is_success": true,
    "message": "Operation completed successfully"
}
```

### Now check after updated values

``` GET http://127.0.0.1:YOUR_PORT/api/v1/values```

Response
```
{
    "is_success": true,
    "key_values": [
        {
            "key": "key1",
            "value": "Kashmir"
        },
        {
            "key": "key2",
            "value": "Sikim"
        },
        {
            "key": "key3",
            "value": "maldives island"
        }
    ]
}
```


### You can dynamically add TTL


### Get TTL value in minutes

``` GET http://127.0.0.1:YOUR_PORT/api/v1/ttl```

Response
```
{
    "is_success": true,
    "ttl": 40
}
```


### Update TTL value min 5 and max 250.

``` POST http://127.0.0.1:YOUR_PORT/api/v1/values```

Request Body
```
{
	"ttl": 5
}
```

Response
```
{
    "is_success": true,
    "message": "Operation completed successfully"
}
```
