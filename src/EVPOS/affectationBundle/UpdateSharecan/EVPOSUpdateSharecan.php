<?php
namespace EVPOS\affectationBundle\UpdateSharecan;

use Doctrine\Common\Collections\ArrayCollection;

class EVPOSUpdateSharecan {
    private $doctrine;
    private $url = 'https://sharecan.strasbourg.eu/projets/moca/_layouts/15/listfeed.aspx?List=%7B5A00CF65%2D6A5E%2D4039%2D941D%2D80B5193416A6%7D&Source=https%3A%2F%2Fsharecan%2Estrasbourg%2Eeu%2Fprojets%2Fmoca%2FApplications%2FForms%2FAvancement%2Easpx';
    private $xml;
    private $opt;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
        $this->opt = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false));
    }
    
    public function updateAvancement() {
        /* Ouverture du flux XML de sharecan */
        $get = file_get_contents($this->url, false, stream_context_create($this->opt));
        

        return $this->url;
    }
}