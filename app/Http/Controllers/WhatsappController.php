<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Whatsapp;

/**
 * Description of Whatsapp
 *
 * @author xscorpio
 */
class WhatsappController extends Controller {

    public function index(){
        return view('sms');
    }
       
    public function send() {                
        
        $usuarioPrueba = new \stdClass();
        //$usuarioPrueba->telefono = "529811322994";
        $usuarioPrueba->telefono = "51993372404";
        $usuarioPrueba->nombre = "Dany";
        $usuarioPrueba->apellidos = "Navarro";
        $usuarioPrueba->dni = "ABC-DEF";
        $usuarioPrueba->password = "1a2b3c4d5e";
        
        // Se debe obtener o generar un array con los ids de usuarios seleccionados en el post
        $usuarios = [ $usuarioPrueba ];     
        
//        $this->sendTo($usuarioPrueba); // single user
        $this->sendTo(...$usuarios); // multiple users
        return 'Enviado...';
    }
    
    private function sendTo(...$usuarios) {
        
        foreach ( $usuarios as $usuario ) {
            
            $telefono = $usuario->telefono;
            $nombreCompleto = $usuario->nombre . " " . $usuario->apellidos;
            $dni = $usuario->dni;
            $password = $usuario->password;
            $body = "Dany Buen día, $nombreCompleto, está inscrito al evento.\n"
                    . "Ingrese con su\n"
                    . "Código: *$dni*\n"
                    . "Clave: *$password*";
            Whatsapp::send($telefono, $body);
            
            // Generamos u obtenemos el pdf a enviar, la URL del archivo o el lugar donde está almacenado, o base64-encoded con el nombre del archivo
            $body = "http://www.africau.edu/images/default/sample.pdf";
            $filename = "prueba.pdf";
            Whatsapp::send($telefono, $body, $filename);

        } 
        
    }
}
