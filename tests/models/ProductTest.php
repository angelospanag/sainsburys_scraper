<?php
/**
 * Unit tests for the Product class
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */

use SainsburysScraper\Models\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @covers Product::getTitle
   */
  public function testTitleIsSet()
  {
    $product = new Product("Title", "13.37kb", 1.50, "Description");
    $title = $product->getTitle();

    $this->assertEquals("Title", $title);
  }

  /**
   * @covers Product::getSize
   */
  public function testSizeIsSet()
  {
    $product = new Product("Title", "13.37kb", 1.50, "Description");
    $size = $product->getSize();

    $this->assertEquals("13.37kb", $size);
  }

  /**
   * @covers Product::getUnitPrice
   */
  public function testUnitPriceIsSet()
  {
    $product = new Product("Title", "13.37kb", 1.50, "Description");
    $unitPrice = $product->getUnitPrice();

    $this->assertEquals(1.50, $unitPrice);
  }

  /**
   * @covers Product::getDescription
   */
  public function testProductDescriptionIsSet()
  {
    $product = new Product("Title", "13.37kb", 1.50, "Description");
    $description = $product->getDescription();

    $this->assertEquals("Description", $description);
  }

  /**
   * @covers Product::jsonSerialize
   */
  public function testJsonSerializing()
  {
    $sampleResponseString = '{"title":"Title","size":"13.37kb","unit_price":1.5,"description":"Description"}';
    $product = new Product("Title", "13.37kb", 1.50, "Description");
    $jsonResponse = json_encode($product);

    $this->assertEquals($sampleResponseString, $jsonResponse);
  }

}
