<?php
/**
 * This file contains a simple object that will contain a product entity
 * along with its relevant information: Title, size of its HTML description page,
 * price per unit and description. For all intents and purposes it can be
 * considered as one of the models of this project.
 *
 * @author Angelos Panagiotopoulos <angelospanag@gmail.com>
 */

namespace SainsburysScraper\Models;

/**
* Product is a class that will hold information about a Sainsbury's
* scraped product.
* It implements a JsonSerializable interface so it can produce
* a JSON of its members.
*/
class Product implements \JsonSerializable
{
    /**
     * The title of the product.
     *
     * @var string
     */
    private $title = '';

    /**
     * Size (in kb) of the linked HTML product page
     *
     * @var string
     */
    private $size = '';

    /**
     * The price per unit of the product.
     *
     * @var float
     */
    private $unitPrice = 0;

    /**
     * The description of the product.
     *
     * @var string
     */
    private $description = '';

    /**
     * Product constructor
     *
     * @param string $title The title of the product
     * @param string $size The size (in kb) of the linked HTML product page
     * @param float $unitPrice The price per unit of the product
     * @param string $description The description of the product
     */
    public function __construct($title, $size, $unitPrice, $description)
    {
        $this->title = $title;
        $this->size = $size;
        $this->unitPrice = $unitPrice;
        $this->description = $description;
    }

    /**
     * Returns the title the product
     *
     * @return string Product title
     */
    public function getTitle()
    {
      return $this->title;
    }

    /**
     * Returns the size (in kb) of the linked HTML of the product
     *
     * @return string Product page size (in kb)
     */
    public function getSize()
    {
      return $this->size;
    }

    /**
     * Returns the price per unit of the product
     *
     * @return float Product price per unit
     */
    public function getUnitPrice()
    {
      return $this->unitPrice;
    }

    /**
     * Returns the description of the product
     *
     * @return string Product description
     */
    public function getDescription()
    {
      return $this->description;
    }

    /**
     * Serializes the Product object to a JSON string.
     *
     * @return array An array representation of the Product class members
     */
    public function jsonSerialize()
    {
          return array('title' => $this->title,
                       'size' => $this->size,
                       'unit_price' => $this->unitPrice,
                       'description' => $this->description);
    }
}
