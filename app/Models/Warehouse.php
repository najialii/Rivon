<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //

protected $fillable = ['name', 'location'];

    public function  inventory()
    {
        return $this->hasMany(inventory::class);
    }

    public function stockMovements()
{
    return $this->hasMany(Stockmovement::class, 'warehouse_id');
}

public function inventories()
{
    return $this->hasMany(Inventory::class);
}
}
