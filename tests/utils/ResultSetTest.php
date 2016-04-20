<?php
/**
 * Unit tests for the ResultSet class
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */

use SainsburysScraper\Models\Product;
use SainsburysScraper\Utils\ResultSet;

class ResultSetTest extends \PHPUnit_Framework_TestCase
{

  /**
   * Tests if the inclusion of multiple products in a ResultSet produces a
   * correct sum of their prices
   *
   * @covers Scraper::getTotal
   */
    public function testSumEqualsRightAmount()
    {
        $product1 = new Product("Avocado", 13.37, 1.3, "Avocado Description");
        $product2 = new Product("Kiwi", 13.37, 11.58, "Kiwi Description");

        $resultSet = new ResultSet();
        $resultSet->addProductToResults($product1);
        $resultSet->addProductToResults($product2);

        $total = $resultSet->getTotal();

        $this->assertEquals(12.88, $total);
    }

    /**
     * Tests the case when no products are in the ResultSet; the sum should be 0
     *
     * @covers Scraper::getTotal
     */
    public function testNoProductsEqualsZeroTotal()
    {
        $resultSet = new ResultSet();
        $total = $resultSet->getTotal();
        $this->assertEquals(0, $total);
    }
}
