<?php
/**
 * This file contains the main logic for scraping a Sainsbury's product page.
 * It is responsible for correctly parsing and sanitizing all the relevant
 * information that will be later used as a JSON response.
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */
namespace SainsburysScraper\Utils;

use SainsburysScraper\Models\Product;
use SainsburysScraper\Utils\ResultSet;

/**
* Scraper is a class that scrapes product information
* from a Sainsbury's product page
*
*/
class Scraper
{
  /**
   * The url of the web page that will be scraped
   *
   * @var string
   */
    private $url;

    /**
     * A ResultSet object that will contain the scraped products
     *
     * @var string
     */
    private $resultSet;

    /**
     * A Client object that will be used from the Goutte scraping library
     *
     * @var object
     */
    private $client;

    /**
    * Scraper constructor
    *
    * @param string $url The url of the web page that will be scraped
    */
    public function __construct($url)
    {
        $this->url = $url;
        $this->resultSet = new ResultSet();
    }

    /**
    * Initiates scraping
    */
    public function crawl()
    {
        $this->client = new \Goutte\Client();

        $crawler = $this->client->request('GET', $this->url);
        $this->crawlProductList($crawler);
    }

    /**
    * Crawls a Sainsbury's web page for products and stores the results
    * in a ResultSet object
    *
    * @param object $crawler A crawler object that scrapes a product's web page
    */
    public function crawlProductList($crawler)
    {
        $crawler->filter('.product')->each(function ($node) {

          try
          {
            //Scrape the product title
            $productTitle =  $this->scrapeProductTitle($node);

            //Scrape the price per unit string of the product
            $productPricePerUnit = $this->scrapeProductPricePerUnit($node);

            //Initialize a subcrawler from the provided link of the product
            $productLink = $node->filter('.productInfo h3 a')->attr('href');
            $subCrawler = $this->client->request('GET', $productLink);

            //Scrape product HTML page size in kb
            $productPageSizeInKb = $this->scrapeProductPageSize($subCrawler);

            //Scrape product description
            $productDescription = $this->scrapeProductDescription($subCrawler);

            //Construct a new Product object using the above information
            $product = new Product($productTitle, $productPageSizeInKb, $productPricePerUnit, $productDescription);

            //Add it to the ResultSet object
            $this->resultSet->addProductToResults($product);
        }
        catch (Exception $e)
        {   //Handle gracefully case for invalid scraping or possible invalid product link
            echo "Scraping for a product failed! Possible invalid product link. Ignoring product from results"."\n";
            echo "Exception: ".$e->getMessage()."\n";
        }
      });
  }

  /**
  * Scrape a product's title from its HTML page
  *
  * @param object $node A node object that contains data that we want to scrape from a product's web page
  * @return string Title of a product
  */
  public function scrapeProductTitle($node)
  {
      $productTitle = trim($node->filter('.productInfo h3')->text());
      return $productTitle;
  }

  /**
  * Scrape a product's price per unit from its HTML page
  *
  * @param object $node A node object that contains data that we want to scrape from a product's web page
  * @return float Price per unit of a product
  */
  public function scrapeProductPricePerUnit($node)
  {
      $productPricePerUnit = trim($node->filter('.pricing > p.pricePerUnit')->text());

      //Sanitize the scraped price per unit string, keep only its numerical float value
      $sanitizedProductPrice = $this->getSanitizedPrice($productPricePerUnit);

      return $sanitizedProductPrice;
  }

  /**
  * Get the size of the HTML page of a product
  *
  * @param object $subCrawler A crawler object that scrapes a product's web page
  * @return string An indicator of an HTML page size of a product (example 35.2kb)
  */
  public function scrapeProductPageSize($subCrawler)
  {
      //Crawl each domElement of the linked product page and create a string from them
      $htmlString = "";
      foreach ($subCrawler as $domElement)
      {
        $htmlString.= $domElement->ownerDocument->saveHTML();
      }
      //Scrape and calculate the product's HTML page size in kb
      $productPageSizeInKb = $this->calculatePageSizeInKb($htmlString);

      return $productPageSizeInKb;
  }

  /**
  * Scrape a product's description from its HTML page
  *
  * @param object $subCrawler A crawler object that scrapes a product's web page
  * @return string Description of a product
  */
  public function scrapeProductDescription($subCrawler)
  {
      //Scrape the description of the product
      $productDescription = trim($subCrawler->filter('.productText')->text());

      return $productDescription;
  }

  /**
  * Accepts a string containing the HTML of a product's page and returns
  * an indicator of its size (in kb)
  *
  * @param string $htmlString A string containing the HTML of a product's page
  * @return string An indicator of a product's HTML page size in kb
  */
  private function calculatePageSizeInKb($htmlString)
  {
      //Page size in bytes
      $pageSizeInBytes = strlen(utf8_decode($htmlString));

      //Page size in kb with decimal scale 2
      $productPageSizeInKb = bcdiv($pageSizeInBytes, '1024', 2);

      //Append the kb indicator
      $pageSizeIndicator = $productPageSizeInKb."kb";

      return $pageSizeIndicator;
  }

  /**
  * Accepts a scraped string containing the price per unit of a product and produces a
  * sanitized numerical float value from it
  *
  * @param string $scrapedPrice A string containing the scraped price per unit of a product. Example £1.50/unit
  * @return float The price of the product as a numerical float value
  */
  private function getSanitizedPrice($scrapedPrice)
  {
      //Regex magic to match the numerical values in the string
      $pattern = '/[0-9]+\.[0-9]+/';
      preg_match($pattern, $scrapedPrice, $matches);
      $priceAsFloat = floatval($matches[0]);

      return $priceAsFloat;
   }

   /**
   * Returns a ResultSet object containing the scraped products
   *
   * @return object A ResultSet object containing the scraped products
   */
   public function getResultSet()
   {
      return $this->resultSet;
   }
}
