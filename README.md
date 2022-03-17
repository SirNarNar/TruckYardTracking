# TruckYardTracking
Code I wrote while doing IT Support co op. It let us know when a problem vehicle was nearby so we could go take a look at it.
Using cURL to pull data from API using a text document with a list of vehicles with problems we need to look at.
Generating array of objects using the data from API.
Then comparing the location data from each object to see if it is in the yard.
If it is, adds the truck name to a string.
Finally if it's between 8 AM and 5:45 PM, sends out an email with the String.
Fairly simple, but extremely useful code for us.
