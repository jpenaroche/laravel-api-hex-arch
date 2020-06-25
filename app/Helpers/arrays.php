<?php

if (! function_exists('hArraysTest')) {
   function hArraysTest($id = null, $autoformat = false) {
       $coll = [
           'Desv' => 'Desvandev',
           'Rand' => 'Random'
       ];

       if(!$autoformat) {
           return $id === null ? $coll : $coll[$id];
       } else {
           $str = [];
           foreach($coll as $k => $v){
               array_push($str, '<option value="'.$k.'" '.($id == $k ? 'selected="selected"' : '').' >'.$v.'</option>');
           }
           return implode("\n", $str);
       }
   }
}

if (!function_exists('array_clear')) {
    function array_clear($array = [], $strict = false)
    {
        $values = array_filter($array, function ($item) {
            return $item !== null && $item !== '';
        });
        if ($strict)
            return array_filter($values, function ($item) {
                return $item !== 0 && $item !== 0.0;
            });
        return $values;

    }
}

if (! function_exists('w2_arrays_currency')) {
   function w2_arrays_currency($id = null, $autoformat = false) {
       $coll = [
           '858' => '$U',
           '840' => 'USD'
       ];

       if(!$autoformat) {
           return $id === null ? $coll : $coll[$id];
       } else {
           $str = [];
           foreach($coll as $k => $v){
               array_push($str, '<option value="'.$k.'" '.($id == $k ? 'selected="selected"' : '').' >'.$v.'</option>');
           }
           return implode("\n", $str);
       }
   }
}

if ( !function_exists('w2_array_departamentos') ) {
   function w2_array_departamentos($id = null, $autoformat = false) {
       $coll = [
           '1'  => ['Artigas', ['lat' => '-30.405991', 'lng' => '-56.470944']],
           '2'  => ['Canelones', ['lat' => '-34.720997', 'lng' => '-55.959035']],
           '3'  => ['Cerro Largo', ['lat' => '-32.376915', 'lng' => '-54.165408']],
           '4'  => ['Colonia', ['lat' => '-34.264247', 'lng' => '-57.610678']],
           '5'  => ['Durazno', ['lat' => '-33.381458', 'lng' => '-56.518581']],
           '6'  => ['Flores', ['lat' => '-33.516943', 'lng' => '-56.898457']],
           '7'  => ['Florida', ['lat' => '-34.099685', 'lng' => '-56.214038']],
           '8'  => ['Lavalleja', ['lat' => '-34.374324', 'lng' => '-55.237146']],
           '9'  => ['Maldonado', ['lat' => '-34.905823', 'lng' => '-54.948893']],
           '10' => ['Montevideo', ['lat' => '-34.8695575', 'lng' => '-56.1663586']],
           '11' => ['Paysandú', ['lat' => '-32.316841', 'lng' => '-58.080090']],
           '12' => ['Río Negro', ['lat' => '-32.700192', 'lng' => '-57.627538']],
           '13' => ['Rivera', ['lat' => '-31.492084', 'lng' => '-55.276680']],
           '14' => ['Rocha', ['lat' => '-34.479014', 'lng' => '-54.335300']],
           '15' => ['Salto', ['lat' => '-31.467934', 'lng' => '-57.101319']],
           '16' => ['San José', ['lat' => '-34.339427', 'lng' => '-56.713160']],
           '17' => ['Soriano', ['lat' => '-33.529522', 'lng' => '-58.216956']],
           '18' => ['Tacuarembó', ['lat' => '-31.715152', 'lng' => '-55.983898']],
           '19' => ['Treinta y tres', ['lat' => '-33.231949', 'lng' => '-54.385652']]
       ];

       if(!$autoformat) {
           return $id === null ? $coll : $coll[$id];
       } else {
           $str = [];
           foreach($coll as $k => $v){
               array_push($str, '<option value="'.$k.'" '.($id == $k ? 'selected="selected"' : '').' >'.$v[0].'</option>');
           }
           return implode("\n", $str);
       }
    }
}
