<?php
include_once("storage.php");

class ProductStorage extends Storage
{
  public function __construct()
  {
    parent::__construct(new JsonIO("./storage/products.json"));
  }
}
