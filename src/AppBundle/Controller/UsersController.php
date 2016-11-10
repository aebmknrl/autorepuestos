<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
	/* le indicamos el método http, el nombre de la acción y action para decirle que esto es una acción del controlador */
    public function getUsersAction()
    {


        $data = array("Usuarios" => array(
        array(
            "nombre"   => "Víctor",
            "Apellido" => "Robles"
        ),
        array(
            "nombre"   => "Antonio",
            "Apellido" => "Martinez"
        )));
         
        return $data;
    }
}
