<?php
namespace App\Helper;


class QrMarge {

     //marge image
     public static function marge_image_portrait($img1, $url){
       
        
        header('Content-Type: image/png');

    

        $dest =imagecreatefromstring($img1);

        $src = imagecreatefrompng(base_path('img/QR-Template.png'));
     
        // Copy and merge
        $data = imagecopymerge( $src,$dest,52, 360.5, 0, 0, 398, 398, 100 );

        $white= ImageColorAllocate($src, 255, 255, 255); 

        //The canvas's (0,0) position is the upper left corner 
        //So this is how far down and to the right the text should start 
        $start_x = 41; 
        $start_y = 852; 


        $font=dirname(__FILE__) .'/MuseoSans_7.otf';


        Imagettftext($src , 25, 0, $start_x, $start_y, $white, $font, $url); 
        imagealphablending($src, false); 
        imagesavealpha($src, true);

        ob_start();

        imagepng($src);
        $image_data = ob_get_contents();

        ob_end_clean();

        imagedestroy($dest);
        imagedestroy($src);

        
        return $image_data;
    }


    public static function marge_image_landscape($img1, $url){

        header('Content-Type: image/png');

    

        $dest =imagecreatefromstring($img1);

        $src = imagecreatefrompng(base_path('img/QrCodeLandscape.png'));

        
     
        // Copy and merge
        $data = imagecopymerge( $src,$dest,23, 21, 0, 0, 158, 158, 100 );

        $white= ImageColorAllocate($src, 255, 255, 255); 

        //The canvas's (0,0) position is the upper left corner 
        //So this is how far down and to the right the text should start 
        $start_x = 206; 
        $start_y = 178; 


        $font=dirname(__FILE__) .'/MuseoSans_7.otf';


        Imagettftext($src , 21, 0, $start_x, $start_y, $white, $font, $url); 
        imagealphablending($src, false); 
        imagesavealpha($src, true);

        ob_start();

        imagepng($src);
        $image_data = ob_get_contents();

        ob_end_clean();

        imagedestroy($dest);
        imagedestroy($src);

        
        return $image_data;
    }
}

