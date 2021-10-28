<?php
namespace Shep\Coach\Traits\Models;

use DB;

trait Funcs
{
    public function getColumns()
    {
       $ret_columns = [];
       foreach ($this->fillable as $column)
       {
            if($this->casts && $this->casts[$column] )
               $ret_columns[$column] = $this->casts[$column];
            else
               $ret_columns[$column] = DB::connection()->getDoctrineColumn($this->getTable(), $column)->getType()->getName();
       }

       return $ret_columns;
    }
}