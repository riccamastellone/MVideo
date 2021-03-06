MVideo
=====================

This is the web app, written in PHP, that will serve as the *controller* of the MVideo battery-consumption-testing project.
It is based on Laravel, which uses Composer: to install just run a `composer update` and everything should be fine.

Requires PHP 5.4+

#### NOTES: 
- The private.key used to ssh the OpenWRT device is excluded from the Git repository for obvious reasons. It has to be placed in the docs/ folder.
- There is a simple `deloy.sh` bash script to help you deploying the app, please have a look at the code before using it.
- There is a flag in the `app/config/mvideo.php` file called `'pretend'`: when set to `TRUE` it will not perform any action on the OpenWRT device.



###Screenshots
![Screenshot1](/docs/screenshots/screenshot-1.png?raw=true)
![Screenshot2](/docs/screenshots/screenshot-2.png?raw=true)


----------
API Calls
---------
The main idea is to let the device communicate to the controller what he is doing, instead of the controller pushing to the device.


#### <i class="icon-file"></i> Get a test
``` 
GET /test
```
Use this call to get a new (or current, read the note) test.

Example response:
```json
{
    "status":"success",
    "message":"New test retrieved",
    "data":
	{
	    "id":"1",
	    "test_id":"12",
	    "media":"uploads\/o_18pk48a6dba5s3vb8u1k8sksh9.jpg",
	    "max_length":"210",
	    "brightness":"80",
	    "network":"wifi",
	    "signal_strenght":"100",
	    "volume":"0",
	    "started":"2014-06-16 08:38:09",
	    "completed":null,
	    "created_at":"2014-06-04 19:03:59",
	    "updated_at":"2014-06-16 08:38:09"
	}
}
```
- *id* -  ID of the single test
- *test_id* - ID of the test group (You may ignore this)
- *media* - Relative media path if the video was uploaded, absolute url if youtube link or manually inserted url
- *max_length* - If the video is longer than this time, the test should stop. (value given in seconds)
- *brightness* - Brightness [0-100]
- *network* - Network to be used [wifi,3g]
- *signal_strenght* - Strenght of the wifi signal (You may ignore this as is a parameter handled by the controller)
- *volume* - Volume level [0-100]

> **NOTE**: As you may notice, the *started* field is not *null*
> This is because this test is marked as started but **not** completed: only one test > at the time can be requested.
> Scroll down for futher information on how the mark a test as started.


> **NOTE2**: The following error message will be shown if no tests are available
>```
> {"status":"error","message":"No test available"}
> ```

#### <i class="icon-search"></i> Queue status
``` 
GET /queue-status
```
Retrieves the status of the queue

Example response:
```json
{
    "queue":0,
    "completed":0,
    "total":1
}
```

#### <i class="icon-pencil"></i> Mark a started test

When the device is ready to start a test, it should mark it as *started* using this call
``` 
POST /start-test
Body: { "test-id" : 123 }
```

#### <i class="icon-off"></i> Handling power
``` 
GET /power/on
```
Turns the USB charging power on

``` 
GET /power/off
```
Turns the USB charging power off

#### <i class="icon-cloud"></i> Sumitting results
To submit the results to the web app, use the following structure
``` 
POST /completed-test
``` 
``` json
{ 
    "test_id" : "10",
    "imei" : 356440041081405,
    "brightness":80.0,
    "volume":60,
    "battery used":1.0,
    "voltage_before":2150,
    "voltage":2100,
    "temperature":250,
    "health":2,
    "technology":-1,
    "wifi status":null,
    "SSID":null,
    "speed":"-1",
    "signal strength":-1,
    "mobile status":"Connected",
    "mobile network type" : "Unknown",
    "data" : {
	"2014-04-02 10:51:53" : "90"
    }
}
```
