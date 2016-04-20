<?php
/**
 * Main entry point of the Sainsbury's Scraper project.
 * Execute this file to initiate the scraping process.
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */
 
namespace SainsburysScraper;

require __DIR__ . '/../vendor/autoload.php';

use SainsburysScraper\Utils\Scraper;
use GuzzleHttp\Exception\ConnectException;

//URL that will be scraped
define("URL", "http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html");

try
{
  //Initiate scraping using the above URL
  $scraper = new Scraper(URL);
  $scraper->crawl();

  //Retrieve a ResultSet object that will contain scraping results
  $resultSet = $scraper->getResultSet();

  //Scraped products
  $products = $resultSet->getProducts();

  //Sum of the products' prices per unit
  $total = $resultSet->getTotal();

  //Array structure that will be printed as a JSON response
  $results = array("results"=>$products,
                   "total"=>$total);

  //Print a JSON response of all the results
  echo json_encode($results, JSON_PRETTY_PRINT);
}
catch (ConnectException $e)
{   //Handle gracefully case for no network connection or invalid URL
    echo "Scraping failed. Please check your network connection and that you are using a valid URL!"."\n";
    echo "Exception: ".$e->getMessage()."\n";
}
