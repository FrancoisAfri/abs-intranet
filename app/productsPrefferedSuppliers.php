<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsPrefferedSuppliers extends Model
{
     //Specify the table name
    public $table = 'products_preferred_suppliers';

    // Mass assignable fields
    protected $fillable = [
        'order_no', 'supplier_id', 'status', 'product_id', 'description', 'inventory_code'];

}
