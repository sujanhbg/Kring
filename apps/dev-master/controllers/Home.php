<?php

use kring\core\Controller;

class Home extends Controller {

    private $model;
    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
        $this->model = $this->loadmodel('home');
    }

    function index($pr) {
        //its need to be call from database which is defined by user
        $data['title'] = "BDEnglish4Exam";
        $data['metadesc'] = "BDEnglish4Exam provides Bangladeshi(BD) learners, examinees and teachers of English with perfect model tests for both academic and competitive exams.";
        $data['leveldata'] = $this->model->get_leveldata();
        $this->tg('home/dashboard.html', $data);
    }

    function level($pr) {
        if (isset($_GET['fd'])) {
            $data['title'] = "Level View";
            echo <<<EOT
            <br>
         <b class="w3-large">Q1. What is the full name of S. T. Coleridge?</b> <br>

             <input type="radio" id="male" name="gender" value="male">
             <label for="male">Shelly Tort Coleridge</label><br>


             <input type="radio" id="male" name="gender" value="male">
             <label for="male">Samuel Tort Coleridge</label><br>


             <input type="radio" id="male" name="gender" value="male" >
             <label for="male">Samuel Taylor Coleridge</label><br>


             <input type="radio" id="male" name="gender" value="male">
             <label for="male">Stuart Tort Coleridge</label><br>


EOT;
        } else {
            $data['title'] = "BDEnglish4Exam";
            $this->index($pr);
        }
    }

}
