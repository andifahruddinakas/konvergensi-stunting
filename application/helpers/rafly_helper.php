<?php
defined('BASEPATH') or exit('No direct script access allowed');

function faker(){
    return Faker\Factory::create('id_ID');
}