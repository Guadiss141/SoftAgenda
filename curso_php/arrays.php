<?php
#array de tipo scalar
$estudiantes=array("carlos","jose","vanessa","caty",43);

echo $estudiantes[4];

#array de multiples dimensiones
$tutor_2=[
    "nombre"=>"Carlos",
    "apellido"=>"Canete",
    "edad"=>20,
    "cursos"=> "PHP","Python","CSS"
];

echo $tutor_2["cursos"][2];