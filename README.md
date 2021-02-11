# Configurable API #

Configurable API is a moodle plugin which allows you to get the data from the configurable reports via an api.
The beauty of this plugin is, you can create multiple instances for the API, using one single token from moodle,
but every single instance will be independent as each of them will have a unique secret key.
For example, you are in a hotel, you have a key for a door, but you can only get access to one room.
Same way, if you have a token from moodle, you are eligible to call the api, but you will only get the data
from your associated secret key for the API instance.

## How to use ##
Prerequisite: Configurable reports plugin, this is where you will create SQL report to use with this plugin.
After you have the plugin and reports ready, go to Site administrator > Plugins > Web services > Configurable API
Here, create an instance, click Create new button to create a new API instance.
Now in the form, give you instance a name, from the dropdown select your desired configurable report.
And now most importantly, enter a secret key for this instance. It is recommended you use a unique one for each
API instances if they are going to be called by different people, that will make sure one doesn't have access to another.
Now save changes, and you will see a sample of the request.
Now go to Site administrator > Plugins > Web services > Manage tokens. Create a token for local_configurable_api.
You are ready to go! use the token, use the combination of id and secret to get the data of the configurable report.

## What type of response I will get ##
You will get a JSON in string format. A sample response is given below:
<RESPONSE>
<SINGLE>
<KEY name="data">
<VALUE>
[{"username":"admin"},{"username":"guest"},{"username":"test1"},{"username":"test2"}]
</VALUE>
</KEY>
</SINGLE>
</RESPONSE>

## Contributing ##
Any type of contribution, suggestions, feature requests is welcome. 
Please create an issue in GitHub to discuss before doing a pull request.
