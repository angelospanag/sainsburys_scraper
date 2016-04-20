<?php
/**
 * Unit tests for the Scraper class
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */

use SainsburysScraper\Models\Product;
use SainsburysScraper\Utils\Scraper;
use Symfony\Component\DomCrawler\Crawler;

class ScraperTest extends \PHPUnit_Framework_TestCase
{

  /**
   * Tests if a float numerical value can be produced from a scraped string
   * with a format like £1.75/kg
   *
   * @covers Scraper::getSanitizedPrice
   */
    public function testSanitizePriceString()
    {
        $scraper = new Scraper("http://asampleurl.test");
        $priceString = '£1.75/kg';
        $pricePerUnit = $this->invokeMethod($scraper, 'getSanitizedPrice', array($priceString));
        $this->assertEquals(1.75, $pricePerUnit);
    }

    /**
     * Tests that a correct size indicator is produced by calculating the size
     * representation of an HTML page
     * Example: a string of 99 bytes should return an indicator: 0.09kb
     *
     * @covers Scraper::calculatePageSizeInKb
     */
    public function testCalculatePageSizeInKb()
    {
        //An HTML string that should be 0.09 kb in size
        $htmlString = "<html><head></head>"
                      ."<body>"
			                ."<h1>A very nice header</h1>"
			                ."<p>Some content in a paragraph</p>"
		                  ."</body>"
                      ."</html>";

        $scraper = new Scraper("http://asampleurl.test");
        $productPageSizeInKb = $this->invokeMethod($scraper, 'calculatePageSizeInKb', array($htmlString));
        $this->assertEquals("0.09kb", $productPageSizeInKb);
    }

    /**
     * Tests that a product title can be successfully scraped from
     * a sample HTML page with similar structure to the Sainsbury's website.
     * This test is used in conjuction with the testProductListPage.html found
     * in the same directory as this test file.
     *
     * @covers Scraper::scrapeProductTitle
     */
    public function testScrapeProductTitle()
    {
      //Use a Symfony crawler object to get our local test HTML page
      $crawler = new Crawler(file_get_contents(__DIR__.'/testProductListPage.html'));

      //Construct a Scraper object that will scrape the HTML page
      $scraper = new Scraper("http://asampleurl.test");
      $crawler->filter('.product')->each(function ($node) use ($scraper)
      {
        $productTitle = $scraper->scrapeProductTitle($node);
        $this->assertEquals("Sainsbury's Apricot Ripe & Ready x5", $productTitle);
      });
    }

    /**
     * Tests that a product price per unit can be successfully scraped from
     * a sample HTML page with similar structure to the Sainsbury's website.
     * This test is used in conjuction with the testProductListPage.html found
     * in the same directory as this test file.
     *
     * @covers Scraper::scrapeProductPricePerUnit
     */
    public function testScrapeProductPricePerUnit()
    {
      //Use a Symfony crawler object to get our local test HTML page
      $crawler = new Crawler(file_get_contents(__DIR__.'/testProductListPage.html'));

      //Construct a Scraper object that will scrape the HTML page
      $scraper = new Scraper("http://asampleurl.test");
      $crawler->filter('.product')->each(function ($node) use ($scraper)
      {
        $productPricePerUnit = $scraper->scrapeProductPricePerUnit($node);
        $this->assertEquals(3.5, $productPricePerUnit);
      });
    }

    /**
     * Tests that a product's HTML page size with similar structure to the
     * Sainsbury's website can be correctly calculated.
     * This test is used in conjuction with the testProductDescriptionPage.html
     * found in the same directory as this test file.
     *
     * @covers Scraper::scrapeProductPageSize
     */
    public function testScrapeProductPageSize()
    {
      //Use a Symfony crawler object to get our local test HTML page
      $crawler = new Crawler(file_get_contents(__DIR__.'/testProductDescriptionPage.html'));

      //Construct a Scraper object that will scrape the HTML page
      $scraper = new Scraper("http://asampleurl.test");
      $productPageSize = $scraper->scrapeProductPageSize($crawler);
      $this->assertEquals("0.25kb", $productPageSize);
    }

    /**
     * Tests that a product description can be successfully scraped from
     * a sample HTML page with similar structure to the Sainsbury's website
     * This test is used in conjuction with the testProductDescriptionPage.html
     * found in the same directory as this test file.
     *
     * @covers Scraper::scrapeProductDescription
     */
    public function testScrapeProductDescription()
    {
      //Use a Symfony crawler object to get our local test HTML page
      $crawler = new Crawler(file_get_contents(__DIR__.'/testProductDescriptionPage.html'));

      //Construct a Scraper object that will scrape the HTML page
      $scraper = new Scraper("http://asampleurl.test");
      $productDescription = $scraper->scrapeProductDescription($crawler);
      $this->assertEquals("Apricots", $productDescription);
    }

    /**
     * Helper method that facilitates testing a private method of a class by
     * making it temporary accessible through Reflection
     *
     * @param object $object The object that contains the private method
     * @param string $methodName The private method that will be tested
     * @param array $parameters The function arguments of the private method
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
