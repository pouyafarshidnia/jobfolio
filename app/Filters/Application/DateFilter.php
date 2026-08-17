<?php

namespace App\Filters\Application;

use EleFilter\Database\ModelFilter;

class DateFilter extends ModelFilter
{
   protected string $column = "submitted_at";

   public function apply(mixed $param): void
   {
      $date = is_array($param) ? sprintf(
         '%04d-%02d-%02d',
         $param['year'],
         $param['month'],
         $param['day']
      ) : null;

      if (null === $date) {
         return;
      }

      $start = $date . ' 00:00:00';
      $end = $date . ' 23:59:59';

      $this->between([$start, $end]);
   }
}
