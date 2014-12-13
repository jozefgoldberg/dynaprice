<?php

namespace Dpp\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DppStatsBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function ligneChartAction()
    {
        // Chart
        $sellsHistory = array(
            array(
                 "name" => "Total des ventes", 
                 "data" => array(683, 756, 543, 1208, 617, 990, 1001)
            ),
                        array(
                 "name" => "Ventes en France", 
                 "data" => array(467, 321, 56, 698, 134, 344, 452)
            ),
            
        );

        $dates = array(
            "21/06", "22/06", "23/06", "24/06", "25/06", "26/06", "27/06"
        );

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('linechart');  
        $ob->title->text('Vente du 21/06/2013 au 27/06/2013');
        
        $ob->yAxis->title(array('text' => "Ventes (milliers d'unité)"));

        $ob->xAxis->title(array('text'  => "Date du jours"));
        $ob->xAxis->categories($dates);

        $ob->series($sellsHistory);

        return $this->render('DppStatsBundle:Default:demo.html.twig', array('linechart' => $ob));
    }
    public function barreChartAction() {
        return $this->allChartAction();
    }
    public function profitsHistoryAction() {
                $sellsHistory = array(
            array(
                 "name" => "Bénéfices total", 
                 "data" => array(9.1, 10.3, 6.5, 12.2, 5.3, 9.1, 11.1)
            ),
            array(
                 "name" => "Bénéfices pour la France", 
                 "data" => array(6.6, 8.2, 0.76, 4.6, 2.1, 4.1, 3.9)
            ),
            
        );

        $dates = array(
            "21/06", "22/06", "23/06", "24/06", "25/06", "26/06", "27/06"
        );

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('graph');  
        $ob->title->text('Bénéfices du 21/06/2013 au 27/06/2013');
        $ob->chart->type('column');
        
        $ob->yAxis->title(array('text' => "Bénéfices (millions d'euros)"));

        $ob->xAxis->title(array('text' => "Date du jours"));
        $ob->xAxis->categories($dates);

        $ob->series($sellsHistory);

        return $this->render('DppStatsBundle:Default:customerStats.html.twig', array('graph' => $ob));
    }
    public function allChartAction()
    {
        $series = array(
            array(
                 "name" => "Bénéfices total", 
                 "data" => array(9.1, 10.3, 6.5, 12.2, 5.2, 9.1, 11.1),
                 "type" => "column"
            ),
            array(
                 "name" => "Bénéfices pour la France", 
                 "data" => array(6.6, 8.2, 0.76, 4.6, 2.1, 4.1, 3.9),
                 "type" => "column"
            ),
            array(
                 "name" => "Total des ventes", 
                 "data" => array(683, 756, 543, 1208, 617, 990, 1001),
                 "type" => "spline",
                 "yAxis" => 1,
            ),
            array(
                 "name" => "Ventes en France", 
                 "data" => array(467, 321, 56, 698, 134, 344, 452),
                 "type" => "spline",
                 "yAxis" => 1,
            ),
            array(
                "type" => "pie",
                "name" => "Pourcentage des ventes totales", 
                "data" => array(
                        array('Guinness', 32.0),
                        array('Westvleteren', 26.8),
                        array('Alchemist Heady Topper', 13.0),
                        array('La Thou', 12.8),
                        array('Russian River Pliny the Elder', 8.5),
                        array('Founders KBS', 6.2),
                        array('Rochefort Trappistes 10', 0.7)
                    ),
                "center" => array(50, 50),
                "size" => 100,  
                "showInLegend" => false,
                "dataLabels" => array(
                    "enabled" => false
                )
            ) 
        );

        $yData = array(
            array(
                'title' => array(
                    'text'  => "Bénéfices (millions d'euros)",
                    'style' => array('color' => '#AA4643')
                ),
                'opposite' => true,
            ),
            array(
                'title' => array(
                    'text'  => "Ventes (milliers d'unités)",
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );

        $dates = array(
            "21/06", "22/06", "23/06", "24/06", "25/06", "26/06", "27/06"
        );

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('linechart');  
        $ob->title->text('Ventes au cours de la semaine');

        $ob->yAxis->title(array('text'  => "Vente en milliers"));
        $ob->yAxis($yData);

        $ob->xAxis->title(array('text'  => "Date du jours"));
        $ob->xAxis->categories($dates);

        $ob->series($series);

        
        return $this->render('DppStatsBundle:Default:demo.html.twig', array('linechart' => $ob));
    }
}
