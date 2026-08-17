<?php

namespace App\Filters\Application;

use EleFilter\Database\ModelFilter;

class SearchFilter extends ModelFilter
{
   protected string $column = "company";

   public function apply(mixed $param): void
   {
      if ($param === null || $param === '') {
         return;
      }

      $this->like($param);
   }
}
