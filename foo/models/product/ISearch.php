<?php

namespace foo\models\Product;

use foo\models\cache\IFetchable;

/**
 * Interface ISearch abstracts the various engines used for searching the product.
 * @package foo\models\Product
 */
interface ISearch extends IFetchable
{

}