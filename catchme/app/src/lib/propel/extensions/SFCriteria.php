<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */
class SFCriteria extends \Propel\Runtime\ActiveQuery\Criteria {


    private $myOrderByColumns = array();


    /**
     * Add an ORDER BY FIELD clause.
     *
     * @param String $name The field to order by.
     * @param array $elements A list to order the elements by.
     * @return SFCriteria
     */
    public function addOrderByField($name, $elements) {
        $this->myOrderByColumns[] = ' FIELD(' . $name . ', ' . join(", ", $elements) . ')';
        return $this;
    }


    public function getOrderByColumns() {
        return array_merge($this->myOrderByColumns, parent::getOrderByColumns());
    }


}