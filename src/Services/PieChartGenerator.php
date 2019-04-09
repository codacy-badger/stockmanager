<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 28/01/2019
 * Time: 17:47
 */

namespace App\Services;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class PieChartGenerator
{

    /**
     * @param $values
     * @param int $height
     * @param int $width
     * @return PieChart
     */
    public function generate($values, int $height, int $width)
    {
        $pieChart = new PieChart();
        $table = [];
        $i = 0;


        while ($i < count($values)) {

            $adds = [
                $values[$i]['name'], (int)$values[$i][1]
            ];

            $table[$i] = $adds;

            $i++;

        }

        $pieChart->getOptions()->setHeight($height);
        $pieChart->getOptions()->setWidth($width);
        $pieChart->getData()->setArrayToDataTable(
            $table, true
        );


        return $pieChart;
    }

}