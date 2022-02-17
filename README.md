# Summary
- xmlparser.php - This is the API endpoint to which POST requests will be made. Returns the json formatted data from the posted xml url
- inc/classes/XMLParser.php - XML parser handler class. Uses no exteral libraries to parse the submitted xml url
- inc/classes/APIClient.php - Class used to handle client post requests

# Requirements
- [Guzzle](https://docs.guzzlephp.org/en/stable/)
- PHP 8.0+

# Server setup
The application should be in the <root webserver directory>/xmlparser folder



# Command line test
Replace the url parameter with any test endpoint

`curl -X POST -F 'url=https://appjobs-general.s3.eu-west-1.amazonaws.com/test-xml-feeds/feed_5.xml'  localhost/xmlparser/xmlparser.php`

# Webpage test
***FYI: This is a very minimalistic and basic webage page to display the results of the xml parsing. This file should not be included in the production release. It is only present here to provide a visual display of the xml parser and to aid with the api testing.***
1. Open the formtest.html page in the web browser
2. Enter any of the test enpoint urls in the text field e.g. https://appjobs-general.s3.eu-west-1.amazonaws.com/test-xml-feeds/feed_6.xml
3. Press the submit button


# Releases
v1.0 - 17/02/2022
