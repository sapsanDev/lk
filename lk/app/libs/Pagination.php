<?php

namespace app\libs;

class Pagination {
    
    private $max = 10;
    private $route;
    private $index = '';
    private $current_page;
    private $total;
    private $limit;

    public function __construct($route, $total, $limit = 10) {
        $this->route = $route;
        $this->total = $total;
        $this->limit = $limit;
        $this->amount = $this->amount();
        $this->setCurrentPage();
    }
   
    public function get() {
        $links = null;
        $limits = $this->limits();
        $html = '<nav><ul class="pagination">';
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
        	if($page == 1 && $this->amount < $this->max){
        		$links .= false;break;
        	}
            if ($page == $this->current_page) {
                $links .= '<li class="page-item active"><span class="page-link">'.$page.'</span></li>';
            } 
            else{
                $links .= $this->generateHtml($page);
            }
        }
        if (!is_null($links)) {
            if ($this->current_page > 1) {
                $links = $this->generateHtml($this->current_page-1, 'Назад').$links;
            }
            if ($this->current_page < $this->amount) {
                $links .= $this->generateHtml($this->current_page+1, 'Вперед');
            }
        }
        $html .= $links.' </ul></nav>';
        return $html;
    }

    private function generateHtml($page, $text = null) {
        if (!$text) {
            $text = $page;
        }
        if(isset($_GET['p']))
        $server = $_GET['p'];
        if(empty($server))$server=1;
        if($server != 1){
        	$a = '<li class="page-item"><a class="page-link" href="?p='.$page.'">'.$text.'</a></li>';
        }elseif(!empty($_GET['p']) == 1){
        	$a = '<li class="page-item"><a class="page-link" href="?p='.$page.'">'.$text.'</a></li>';
        }
        else $a = '<li class="page-item"><a class="page-link" href="?p='.$page.'">'.$text.'</a></li>';
        return $a;
    }

    private function limits() {
        $left = $this->current_page - round($this->max / 2);
        $start = $left > 0 ? $left : 1;
        if ($start + $this->max <= $this->amount) {
            $end = $start > 1 ? $start + $this->max : $this->max;
        }
        else {
            $end = $this->amount;
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }
        return array($start, $end);
    }

    private function setCurrentPage() {
        if (isset($this->route)) {
            $currentPage = $this->route;
        } else {
            $currentPage = 1;
        }
        $this->current_page = $currentPage;
        if ($this->current_page > 0) {
            if ($this->current_page > $this->amount) {
                $this->current_page = $this->amount;
            }
        } else {
            $this->current_page = 1;
        }
    }

    private function amount() {
        return ceil($this->total / $this->limit);
    }
}