<?php
#// src\Dpp\StatsBundle\Controller\CustomerStatsController.php

namespace Dpp\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

class CustomerStatsController extends Controller
{
    private $entityManager = null;
    private $customer = null;
    
    public function indexAction() 
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect( $this->generateUrl('dpp_customers_list') );
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        $this->customer = $user->getCustomer();
        if (!$this->customer == null) {
            return $this->listByCustomer();
        }
        throw new AccessDeniedException("Vous n'étez pas autorisé.");       
    }
    
    public function customerStatsAction($customerRef)         
    {
       
        if ($this->entityManager == null) {
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $this->customer = $this->entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if (!$this->customer == null) {
            return $this->listByCustomer();
        }
        throw new AccessDeniedException("Vous n'étez pas autorisé.");     
    }
    
    private function listByCustomer() {
    
        $today = new \DateTime(date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d"), date("Y"))));
        $today_1 = new \DateTime(date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))));
        $month = new \DateTime(date('Y-m-d', mktime(0, 0, 0, date("m")  , 1 , date("Y"))));
        $month_end =  new \DateTime(date_sub( new \DateTime(date('Y-m-d',mktime(0, 0, 0, date("m")+1  , 1, date("Y")))),date_interval_create_from_date_string('1 days'))->format('Y-m-d'));
        $year = new \DateTime(date('Y-m-d', mktime(0, 0, 0, 1  , 1, date("Y"))));
        $year_1 = new \DateTime(date('Y-m-d', mktime(0, 0, 0, 1  , 1, date("Y")-1)));
        $month_1 = new \DateTime(date('Y-m-d', mktime(0, 0, 0, date("m")-1  , 1, date("Y"))));
        $month_1_end = new \DateTime(date_sub(new \DateTime(date('Y-m-d', mktime(0, 0, 0, date("m")  , 1 , date("Y")))) ,date_interval_create_from_date_string('1 days'))->format('Y-m-d'));
        $year_end = new \DateTime(date_sub( new \DateTime(date('Y-m-d',mktime(0, 0, 0, 1  , 1, date("Y")+1))),date_interval_create_from_date_string('1 days'))->format('Y-m-d'));
        $year_1_end = new \DateTime(date_sub(new \DateTime(date('Y-m-d', mktime(0, 0, 0, 1  , 1, date("Y")))) ,date_interval_create_from_date_string('1 days'))->format('Y-m-d'));
    
    
        if ($this->entityManager == null) {
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $responseProduct = $this->entityManager->getRepository('DppCustomersBundle:Product')->getCountForCustomer($this->customer);
        $responseCategory = $this->entityManager->getRepository('DppCustomersBundle:Category')->getCountForCustomer($this->customer);
        
        $nbProducts = $responseProduct [0]['count'];
        $nbCategorys = $responseCategory [0]['count'];
        
        
        $tabVisitMM = $this->entityManager->getRepository('DppAjaxServeurBundle:Log')->getAccessProduct($this->customer, $month, $month_end);
        $tabVisitM1 = $this->entityManager->getRepository('DppAjaxServeurBundle:Log')->getAccessProduct($this->customer, $month_1, $month_1_end);
        $graph = $this->createGraphic($tabVisitMM, 'graphMonth', $month, $month_end); 
        $graph1 = $this->createGraphic($tabVisitM1, 'graphMonth1', $month_1, $month_1_end); 
        
        return $this->render('DppStatsBundle:Default:customerStats.html.twig', array('graphMonth' => $graph, 'graphMonth1' => $graph1,
                                                                                     'customer' => $this->customer,
                                                                                     'nbProducts' => $nbProducts,
                                                                                     'nbCategorys' => $nbCategorys));
    }
    private function createGraphic($tabVisit, $nameGraph, $datedeb, $datefin) {  
        $dates = null;
        $visites = null;  
        $deb=true;
        foreach($tabVisit as $ligTab) {
            if($deb) {
                $deb=false;
                $jour=intval(substr($ligTab['jour'], 8));
                for($i=1; $i < $jour;  $i++) {
                    $visites[] = 0;
                    $dates[]  = date('d', mktime(0, 0, 0, date("m")  , $i, date("Y")));
                }
            }
            $visites[] = intval($ligTab['count']);
            $dates[] =substr($ligTab['jour'], 8);
        }
        $valueSeries = array( array( "name"=>"Nombre des visites",
                                    "data"=>$visites));
        $ob = new Highchart();
        $ob->chart->renderTo($nameGraph);
        $ob->title->text('Activité du: ' . $datedeb->format('d-m-Y').' au '. $datefin->format('d-m-Y'));
        $ob->yAxis->title(array('text' => "Nombre"));
        $ob->xAxis->title(array('text' => "Date du jour"));
        $ob->xAxis->categories($dates);
        $ob->series($valueSeries);
        return $ob;
    }
        
/*    
    private function listByCustomer() {
        if ($this->entityManager == null) {
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $nbProduct = $this->entityManager->getRepository('DppCustomersBundle:Product')->getCountForCustomer($this->customer);
        
        $tabVisit = $this->entityManager->getRepository('DppAjaxServeurBundle:Log')->getMixteCustomerByDay($this->customer);
        $dates = null;
        $resDate = null;
        $visites = null;
        $achats = null;
        $topAchat = True;
        foreach($tabVisit as $ligTab) {
            if ($ligTab['type'] == 'ACC') {
                if (!$topAchat) {   // pour la date precedant il n'y a eu d'achats
                    $achats[] = 0;   // on met a zero
                }
                $topAchat = false;
                $visites[] = intval($ligTab['count']);
                $dates[] =$ligTab['jour'];
            } else {
                $achats[] = intval($ligTab['count']);
                $topAchat = true;
            }
        }
        $valueSeries = array( array( "name"=>"Nombre des visites",
                                    "data"=>$visites),
                              array( "name"=>"Nombre d'achats",
                                    "data"=>$achats),
                            );
        // var_dump($valueSeries);
        $ob = new Highchart();
        $ob->chart->renderTo('graph');
        $ob->title->text('Activité journalière');
        $ob->chart->type('column');
        
        $ob->yAxis->title(array('text' => "Nombre"));

        $ob->xAxis->title(array('text' => "Date du jour"));
        $ob->xAxis->categories($dates);
        $ob->series($valueSeries);

        return $this->render('DppStatsBundle:Default:customerStats.html.twig', array('graph' => $ob,
                                                                                      'customer' => $this->customer,
                                                                                      'nbProducts' => 6));
 */  
    
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
