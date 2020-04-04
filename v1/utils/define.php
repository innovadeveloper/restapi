<?php
// define('C_NewLine', chr(10));
// registrando los dominios de los servicios de scraping

$pathFileApiScraping = "handler_html?";
$firstDomain = "https://scrapingdeveloper2.000webhostapp.com/v1/$pathFileApiScraping";
$secondDomain = "https://scrapingdevelop.000webhostapp.com/v1/$pathFileApiScraping";
$thirdDomain = "https://scrapingdevelop3.000webhostapp.com/v1/$pathFileApiScraping";
// $thirdDomain = "http://localhost:8888/restapi/v1/$pathFileApiScraping";

$pathFileApiTranslator = "translator?";
$firstDomainTranslator = "https://scrapingdeveloper2.000webhostapp.com/v1/$pathFileApiTranslator";
$secondDomainTranslator = "https://scrapingdevelop.000webhostapp.com/v1/$pathFileApiTranslator";
$thirdDomainTranslator = "https://scrapingdevelop3.000webhostapp.com/v1/$pathFileApiTranslator";
// $thirdDomainTranslator = "http://localhost:8888/restapi/v1/$pathFileApiTranslator";


$pathFileApiHttGet = "handler_api?";
$firstDomainHttGet = "https://scrapingdeveloper2.000webhostapp.com/v1/$pathFileApiHttGet";
$secondDomainHttGet = "https://scrapingdevelop.000webhostapp.com/v1/$pathFileApiHttGet";
$thirdDomainHttGet = "https://scrapingdevelop3.000webhostapp.com/v1/$pathFileApiHttGet";
// $thirdDomainHttGet = "http://localhost:8888/restapi/v1/$pathFileApiHttGet";


define('C_NewLine', "<br />");
define('LIST_DOMAINS_SCRAPING', array($firstDomain, $secondDomain, $thirdDomain));
// define('LIST_DOMAINS_SCRAPING', array($thirdDomain));

define('LIST_DOMAINS_TRANSLATOR', array($firstDomainTranslator, $secondDomainTranslator, $thirdDomainTranslator));

define('LIST_DOMAINS_HTTP_GET', array($firstDomainHttGet, $secondDomainHttGet, $thirdDomainHttGet));
// define('LIST_DOMAINS_HTTP_GET', array($thirdDomainHttGet));
?>