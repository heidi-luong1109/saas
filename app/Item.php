<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'sale_price',
        'purchase_price',
        'tax',
        'category',
        'unit',
        'type',
        'description',
        'created_by',
    ];


    public function units()
    {
        return $this->hasOne('App\Unit', 'id', 'unit');
    }

    public function categories()
    {
        return $this->hasOne('App\Category', 'id', 'category');
    }

    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = TaxRate::find($tax);
        }

        return $taxes;
    }

    public function taxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        foreach($taxArr as $tax)
        {
            $tax     = TaxRate::find($tax);
            $taxRate += $tax->rate;
        }

        return $taxRate;
    }

    public function taxes()
    {
        return $this->hasOne('App\TaxRate', 'id', 'tax');
    }


}
