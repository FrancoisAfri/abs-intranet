<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_products extends Model
{
    protected $table = 'Product_products';
    protected $fillable = ['name', 'description', 'status', 'category_id', 'productPrice_id', 'product_code', 'stock_type'
	, 'is_vatable'];

    // Product & category
    public function ProductPackages()
    {
        return $this->belongsTo(product_category::class, 'category_id')->orderBy('id');
    }
	
	public function stocks()
    {
        return $this->hasOne(stock::class, 'product_id');
    }
	
    public function PackadgesTypes()
    {
        return $this->belongsToMany('App\product_packages', 'packages_product_table', 'product_product_id', 'product_packages_id')->withPivot('description');
    }

    /**
     * The relationships between product and promotion.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promotions()
    {
        return $this->hasMany(product_promotions::class, 'product_product_id');
    }
	
	public function infosProduct()
    {
        return $this->hasMany(stockInfo::class, 'product_id');
    }
	public function productLocation()
    {
        return $this->hasMany(Stock_location::class, 'product_id');
    }
	
	public function preferredProduct()
    {
        return $this->hasMany(productsPreferredSupplier::class, 'product_id');
    }

    /**
     * The relationships between product and price.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productPrices()
    {
        return $this->hasMany(product_price::class, 'product_product_id');
    }

    /**
     * The function to add a new price for a product.
     *
     * @param product_price $price
     * @return Model [saved price]
     */
    public function addNewPrice($price)
    {
        return $this->productPrices()->save($price);
    }

    /**
     * Relationship between Products and Quotations
     *
     * @return
     */
    public function quotations()
    {
        return $this->belongsToMany('App\Quotation');
    }

    public static function movproductCategory($whereField, $whereValue, $incInactive)
    {
        $model = product_products::where(function ($query) use ($whereValue, $whereField) {
            if ($whereValue == 0) $query->whereNull($whereField);
            else $query->where($whereField, $whereValue);
            //$query->where();
        })
            ->where(function ($query) use ($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })
            ->whereBetween('Product_products.stock_type', [1, 3])
            ->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $model;
    }
}
