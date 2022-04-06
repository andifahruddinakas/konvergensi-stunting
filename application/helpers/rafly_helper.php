<?php
defined('BASEPATH') or exit('No direct script access allowed');

function d($x){
    return die(json_encode($x));
}