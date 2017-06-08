<?php

namespace foo\models\Product;

use foo\models\cache\IFetchable;

/**
 * Interface ISearch abstracts the various engines used for searching the product.
 * Note that it just extends IFetchable - but not every IFetchable is an ISearch.
 * @package foo\models\Product
 */
interface ISearch extends IFetchable
{

}
