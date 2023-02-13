<?php

// DONT CHANGE THIS

/*  ================[INFO]================
 *   AUTHOR  : ZLAXTERT
 *   SCRIPT  : CARRIER
 *   GITHUB  : https://github.com/ZLAXTERT
 *   IG      : https://instagram.com/zlaxtert
 *   VERSION : 1.1 (CLI)
 *  ======================================
 */

//SETTING 

ini_set("memory_limit", '-1');
date_default_timezone_set("Asia/Jakarta");
define("OS", strtolower(PHP_OS));
//============> CREATE FOLDER RESULT
$DirRess = "result/";
if(is_dir($DirRess)){
    $DirRess2 = "result/die/";
    if(is_dir($DirRess2)){
        // TRUE
    }else{
        mkdir($DirRess2);
    }
}else{
    mkdir($DirRess);
    $DirRess2 = "result/die/";
    if(is_dir($DirRess2)){
        // TRUE
    }else{
        mkdir($DirRess2);
    }
}

$date = date("l, d-m-Y");

//BANNER

system("cls");
echo banner();

//INPUT LIST

enterlist:
echo "\n[+] Enter your list (eg: list.txt) >> ";
$listname = trim(fgets(STDIN));
if(empty($listname) || !file_exists($listname)) {
	echo " [!] Your Fucking list not found [!]".PHP_EOL;
	goto enterlist;
}
$lists = array_unique(explode("\n",str_replace("\r","",file_get_contents($listname))));
$apikey = file_get_contents("apikey.app");


//COUNT

$l = 0;
$d = 0;
$e = 0;
$u = 0;
$no = 0;
$total = count($lists);
echo "\n[+] TOTAL $total lists [+]\n\n";

//LOOPING

foreach ($lists as $list) {
     $no++;
     //API
     $url = "http://api.blacknetid.com/validator/carrier/?apikey=$apikey&phone=$list";
     
     //CURL
     
    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    $js = json_decode($res, TRUE);
    $carrierName  = $js['data']['info']['carrier'];
    $carrierName  = str_replace("\\","",$carrierName);
    $carrierName  = str_replace("/","",$carrierName);
     
     //RESPONSE
     
     if(strpos($res, '"carrier":"WRONG API KEY!"')){
        $e++;
        exit("\n\n[\e[31;1mX\e[0m] \e[33;1m!!!\e[31;1mWRONG API KEY\e[33;1m!!! \e[0m[\e[31;1mX\e[0m]\n\n\n");
     }elseif(strpos($res, '"valid":"true"')){
         $l++;
         file_put_contents("result/$carrierName.txt", $list.PHP_EOL, FILE_APPEND);
         echo "[$no/$total] \e[32;1mLIVE\e[0m | $list => \e[32;1m$carrierName\e[0m \n";
     }elseif(strpos($res, '"valid":"false"')){
         $d++;
         file_put_contents("result/die/$carrierName.txt", $list.PHP_EOL, FILE_APPEND);
         echo "[$no/$total] \e[31;1mDIE\e[0m | $list | => \e[31;1m$carrierName\e[0m \n";
     }elseif(strpos($res, "The server is temporarily busy, try again later!")){
         $e++;
         file_put_contents("result/error.txt", $list.PHP_EOL, FILE_APPEND);
         echo "[x] !!!SERVER BUSY!!! [x]\n";
     }else{
         $u++;
         file_put_contents("result/unknown.txt", $list.PHP_EOL, FILE_APPEND);
         echo "[$no/$total] \e[33;1mUNKNOWN\e[0m => $list \n";
     }
}

//END

echo "
DATE : $date
==========[INFO]==========
  TOTAL LIST : $total
  LIVE : $l
  DIE : $d
  UNKNOWN : $u
  ERROR : $e 
==========================
     THANKS FOR USING
";

function banner(){
    $banner = "
                                                                                    
            ╦  ╦┌─┐┬  ┬┌┬┐┌─┐┌┬┐┌─┐┬─┐  ╔═╗┌─┐┬─┐┬─┐┬┌─┐┬─┐
            ╚╗╔╝├─┤│  │ ││├─┤ │ │ │├┬┘  ║  ├─┤├┬┘├┬┘│├┤ ├┬┘
             ╚╝ ┴ ┴┴─┘┴─┴┘┴ ┴ ┴ └─┘┴└─  ╚═╝┴ ┴┴└─┴└─┴└─┘┴└─ V.1.0
                         https://blacknetid.com
                           [CODE BY ZLAXTERT]
            ================================================
";
    return $banner;
}
