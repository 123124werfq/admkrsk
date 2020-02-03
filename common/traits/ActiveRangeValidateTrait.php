<?php

namespace common\traits;

use yii\db\ActiveQuery;

trait ActiveRangeValidateTrait
{
    /**
     * @param string $number
     * @param ActiveQuery $query
     * @param string $attributeName
     */
    private function handleNumberRange($number, &$query, $attributeName)
    {
        if (preg_match('/^\d+$/', $number, $matches)) {
            $query->andFilterWhere([$attributeName => intval($number)]);
        }
        if (preg_match('/\d+-\d+/i', $number)) {
            list($startNumber, $endNumber) = explode('-', $number);
            $query->andFilterWhere(['between', $attributeName, intval($startNumber), intval($endNumber)]);
        }
    }

    /**
     * @param string $date
     * @param ActiveQuery $query
     * @param string $attributeName
     */
    private function handleDateRange($date, &$query, $attributeName)
    {
        try {
            if (preg_match('/^(\d{4}-\d{2}-\d{2})$/i', $date, $mat)) {
                $query->andFilterWhere(['<=', $attributeName, strtotime($date)]);
            }
            if (preg_match('/^(\d{4}-\d{2}-\d{2}) до (\d{4}-\d{2}-\d{2})$/i', $date)) {
                list($startDate, $endDate) = explode(' до ', $date);
                if (preg_match('/\d{4}-\d{2}-\d{2}/i', $startDate)
                    && preg_match('/\d{4}-\d{2}-\d{2}/i', $endDate)) {
                    $query->andFilterWhere(['between', $attributeName, strtotime($startDate), strtotime($endDate)]);
                }
            }
        } catch (\Exception $exception) {
            throw new  \ParseError("invalid {$attributeName}");
        }
    }
}