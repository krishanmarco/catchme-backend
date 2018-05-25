<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Propel\Extensions;
use Propel\Runtime\ActiveQuery\ModelCriteria;


abstract class QueryHelper {

    public static function fullTextSearch(ModelCriteria $critiera, $columnName, $searchString, $groupByCol = null) {
        $query = $critiera->where('MATCH(' . $columnName . ') AGAINST(? IN BOOLEAN MODE)', $searchString);

        if (!is_null($groupByCol))
            $query = $query->groupBy($groupByCol);

        return $query->find();
    }

}