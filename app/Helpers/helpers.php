<?php

if (! function_exists('w2_isactive')) {
   function w2_isactive($current, $target) {
      return ($current == $target) ? 'is-active' : '';
   }
}

if (! function_exists('w2_sino')) {
   function w2_sino($bool, $yes_icon = null, $no_icon = null) {
       $yes_str = ['Si', '<span class="icon has-text-success"><i class="fa fa-'.($yes_icon === true ? 'check-circle' : $yes_icon).'"></i></span>'];
       $no_str = ['No', '<span class="icon has-text-grey-lighter"><i class="fa fa-'.($no_icon === true ? 'times-circle' : $no_icon).'"></i></span>'];
      return ($bool) ? (is_null($yes_icon) ? $yes_str[0] : $yes_str[1]) : (is_null($no_icon) ? $no_str[0] : $no_str[1]);
   }
}

if (! function_exists('w2_woc_priceFormat')) {
   function w2_woc_priceFormat($float) {
       $formated = number_format($float, 2, ',', '.');
       $formated = str_replace(',00','',$formated);
       return $formated;
   }
}

if (! function_exists('w2_woc_priceFloat')) {
   function w2_woc_priceFloat($str) {
       // convert "," to "."
        $str = str_replace(',', '.', $str);
        // remove everything except numbers and dot "."
        $str = preg_replace("/[^0-9\.]/", "", $str);
        // remove all seperators from first part and keep the end
        $str = str_replace('.', '',substr($str, 0, -3)) . substr($str, -3);
        // return float
        return (float) $str;
   }
}

if (! function_exists('w2_set_date_format')) {
   function w2_set_date_format($str) {
       $date = date_create_from_format("d/m/Y", $str);
       $date = date_format($date, "Y-m-d 00:00:00");
       return $date;
   }
}

if (! function_exists('w2_set_date_format_DMY')) {
   function w2_set_date_format_DMY($str) {
       $date = date_create_from_format("Y-m-d H:i:s", $str);
       $date = date_format($date, "d/m/Y");
       return $date;
   }
}

if (! function_exists('w2_set_checkbox')) {
   function w2_set_checkbox($str) {
       if( $str === 1 || $str === 0 ) return $str;

       $check = ($str[0] === 'on'  ? 1 : 0);
       return $check;
   }
}

if (! function_exists('w2img')) {
   function w2img($path) {
      return asset('admin_assets/img/'.$path);
   }
}
if (! function_exists('w2css')) {
   function w2css($path) {
      return asset('admin_assets/css/'.$path);
   }
}
if (! function_exists('w2js')) {
   function w2js($path) {
      return asset('admin_assets/js/'.$path);
   }
}

if (! function_exists('pw2img')) {
   function pw2img($path) {
      return asset('page_assets/img/'.$path);
   }
}
if (! function_exists('pw2css')) {
   function pw2css($path) {
      return asset('page_assets/css/'.$path);
   }
}
if (! function_exists('pw2js')) {
   function pw2js($path) {
      return asset('page_assets/js/'.$path);
   }
}
