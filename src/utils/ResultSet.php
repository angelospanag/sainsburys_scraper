<?php
/**
 * This file is responsible for organizing all the data that was received
 * during the scraping operation. All scraped products and their sum of their
 * prices per unit are accessible here.
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */

namespace SainsburysScraper\Utils;

/**
* ResultSet is a class that will hold the results of a scraping
* operation. More specifically an array of Product objects and a
* numerical float value representing the sum of their prices per unit.
*/
class ResultSet
{

  /**
   * An array of Product objects
   *
   * @var array
   */
  private $products = array();

  /**
   * The sum of all prices per unit of the Product objects
   *
   * @var float
   */
  private $total = 0;

  /**
   * ResultSet constructor
   */
  public function __construct()
  {}

  /**
    * Adds a Product to the ResultSet and amends the sum of their prices per unit
    *
    * @param object $product A Product object
    */
  public function addProductToResults($product)
  {
      $this->products[] = $product;
      $price = $product->getUnitPrice();

      //Sum of all product prices with decimal scale 2
      $this->total = bcadd($this->total, $price, 2);
  }

  /**
   * Returns an array of all the Products in the ResultSet
   *
   * @return array An array of all the Products in the ResultSet
   */
  public function getProducts()
  {
      return $this->products;
  }

  /**
   * Returns the sum of the prices per unit of all the Products in the ResultSet
   *
   * @return float The sum of the prices per unit of all the Products in the ResultSet
   */
  public function getTotal()
  {
      return floatval($this->total);
  }
}
