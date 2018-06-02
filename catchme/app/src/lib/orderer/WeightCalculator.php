<?php /** Created by Krishan Marco Madan on 02-Jun-18. */

namespace WeightedCalculator;
use Closure;

class WeightedGroupCalculator implements IWeightCalculator {

    public function __construct($iWeightCalculators) {
        $this->iWeightCalculators = $iWeightCalculators;
    }

    /** @var IWeightCalculator[] */
    private $iWeightCalculators;

    /**
     * Since this is the highest weight calculator
     * Its weight is always 1
     * @return int
     */
    public function getWeight() {
        return 1;
    }

    /** @return WeightedUnit[] */
    public function calculateUniqueAccumulatedSimple() {
        return $this->calculateUniqueAccumulated(function(WeightedUnit $wu1, WeightedUnit $wu2) {
            return $wu1->data === $wu2->data;
        });
    }

    /** @return WeightedUnit[] */
    public function calculate() {
        return WeightHelper::orderByWeightsDesc($this->calculateUnordered());
    }

    /** @return WeightedUnit[] */
    public function calculateUniqueAccumulated(Closure $isDataSame) {
        /** @var WeightedUnit[] $result */
        $result = [];

        $weightedUnits = $this->calculateUnordered();
        foreach ($weightedUnits as $wu) {
            $idxOfDuplicate = -1;

            for ($i = 0; $i < sizeof($result); $i++) {
                if ($isDataSame->call($this, $wu, $result[$i])) {
                    $idxOfDuplicate = $i;
                    break;
                }
            }

            if ($idxOfDuplicate === -1) {
                $result[] = $wu;
            } else {
                $result[$idxOfDuplicate]->weight += $wu->weight;
            }
        }

        return WeightHelper::orderByWeightsDesc(WeightHelper::normalizeWeights($result));
    }

    /** @return WeightedUnit[] */
    private function calculateUnordered() {

        /** @var WeightedUnit[] $dataList */
        $dataList = [];

        // Calculate all the sub iWeightedCalculators and merge the results
        foreach ($this->iWeightCalculators as $iWeightCalculator) {
            $subWeightedUnits = $iWeightCalculator->calculate();

            // Apply the weightedUnits global weight to each item
            foreach ($subWeightedUnits as $iwu)
                $iwu->weight = $iwu * $iWeightCalculator->getWeight();

            // Add the results to the result dataList
            $dataList = array_merge($dataList, $subWeightedUnits);
        }

        return WeightHelper::normalizeWeights($dataList);
    }

}

interface IWeightCalculator {

    /**
     * Returns the weight this IWeightCalculator
     * has in it's parents space
     * @return int
     */
    public function getWeight();

    /**
     * Returns all the weighted units
     * @return WeightedUnit[]
     */
    public function calculate();
}

class WeightCalculator implements IWeightCalculator {

    public function __construct($weight, Closure $calculator) {
        $this->weight = $weight;
        $this->calculator = $calculator;
    }

    /** @var float */
    private $weight;

    /** @var Closure */
    private $calculator;

    public function getWeight() {
        return $this->weight;
    }

    public function calculate() {
        return $this->calculator->call($this);
    }

}

class WeightedUnit {

    public function __construct($data, $weight = 1) {
        $this->data = $data;
    }

    /** @var float The weight this item has */
    public $weight;

    /** @var mixed The data */
    public $data;
}

class WeightHelper {

    /**
     * @param WeightedUnit[] $weightedUnits
     * @return WeightedUnit[]
     */
    public static function orderByWeightsDesc(array $weightedUnits) {
        usort($dataList, function(WeightedUnit $wu1, WeightedUnit $wu2) {
            return $wu1->weight > $wu2->weight ? -1 : 1;
        });
        return $weightedUnits;
    }

    /**
     * @param WeightedUnit[] $weightedUnits
     * @return WeightedUnit[]
     */
    public static function normalizeWeights(array $weightedUnits) {
        $sumOfWeights = 0;

        foreach ($weightedUnits as $wu)
            $sumOfWeights += $wu->weight;

        foreach ($weightedUnits as $wu)
            $wu->weight = $wu->weight / $sumOfWeights;

        return $weightedUnits;
    }

}