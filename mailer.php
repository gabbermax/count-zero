<?php
//class sql;;
require_once ('dbase.php');

function recursive_search($structure){

    $encoding = "";

    if($structure->subtype == "HTML" ||
        $structure->type == 0){

        if($structure->parameters[0]->attribute == "charset"){

            $charset = $structure->parameters[0]->value;
        }

        return array(
            "encoding" => $structure->encoding,
            "charset"  => strtolower($charset),
            "subtype"  => $structure->subtype
        );
    }else{

        if(isset($structure->parts[0])){

            return recursive_search($structure->parts[0]);
        }else{

            if($structure->parameters[0]->attribute == "charset"){

                $charset = $structure->parameters[0]->value;
            }

            return array(
                "encoding" => $structure->encoding,
                "charset"  => strtolower($charset),
                "subtype"  => $structure->subtype
            );
        }
    }
}

function convert_to_utf8($in_charset, $str){

    return iconv(strtolower($in_charset), "utf-8", $str);
}

$resource =imap_open('{imap.yandex.ru:993/imap/ssl}INBOX','*******','***************');
$list = imap_list($resource, '{imap.yandex.ru:993/imap/ssl}', '*');

$urgent=imap_search($resource,'FLAGGED',SE_UID);
//there lot of flagger messases
$tempCount=count($urgent);
$db_con->multi_query('set character_set_client="utf8"');
$mNumber=[];
echo time(),"<br><br>";
for ($i=0;$i<$tempCount;$i++){
    //get id of mail message
    $mNumber[$i]=imap_msgno($resource,$urgent[$i]);
    $message_head=imap_header($resource,$mNumber[$i]);
    $eSender= $message_head->senderaddress;
    $eDate=$message_head->date;
    $eHeader=$message_head->subject;
    $eBody=(imap_body($resource,$mNumber[$i]));
    $query="insert into email1 (header,sender,message) values ('"
        .$eHeader."','"
        .$eSender."','"
        .$eBody."'
    )";

    $db_con->multi_query($query);
   // echo mysqli_error($db_con);

}
echo time();
imap_close($resource);

//$zz2=imap_uid($resource,$zz);


/*$message_head=imap_header($resource,$zz3);
$eSender= $message_head->senderaddress;
$eDate=$message_head->date;
$eHeader=$message_head->subject;
$temp=imap_body($resource,$zz3);

$msg_structure=imap_fetchstructure($resource,$zz3);
$recursive_data = recursive_search($msg_structure);*/
//echo $recursive_data["encoding"];
//$eBody=(imap_body($resource,$zz3));
//$eBodyTemp=imap_body($resource,$zz3);
//$temp_convert=(convert_to_utf8('utf8',$eBodytemp));
//echo "$sender   message";
/*foreach ($message_head as $key=>$value){
   print_r($key); // {$value} ";
   echo "<br>";
echo "<br>";
print_r($value);
echo "<br>";
echo "<br>";
}*/
//var_dump($message_head);

/*/$MC = imap_check($resource);
$result = imap_fetch_overview($resource,"1:{$MC->Nmsgs}",0);
//$result= imap_base64($result);
foreach ($result as $overview) {
    echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from}
    {$overview->subject}\n <br> <br><br>";
}*/

//$message_body= imap_fetchbody($resource, $zz3, $)
//var_dump($decoded);

//=?utf-8?B?0LzQsNC60YHQuNC8LCDQktCw0Ygg0LHQsNC70LDQvdGBINCyICLQnNC+0Lkg?= =?utf-8?B?0KHQsNC50LTQtdC60YEi?=
imap_close($resource);

/*
$eSender= $message_head->senderaddress;
$eDate=$message_head->date;
$eHeader=$message_head->subject;
$db_con->multi_query($query);*/



//todo timestamp
//todo brocken encoding
//todo dbase store only message id
//todo  send to dbase
//todo heavy
?>
