<?php
namespace EVPOS\affectationBundle\Update;

class EVPOSupdate
{
    public function updateNexthinkUo($codeUo) {
        $url = "https://srvpnextp.cus.fr:1671/1/investigations/0388309704/liste%20utilisateur%20par%20code%20UO?"+$codeUo;
        return $url;
    }
}
