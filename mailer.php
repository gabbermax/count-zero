<?php
//todo timestamp in sql
    //ready
//todo brocken encoding
    //2 types of encoding ,looks works
//todo encoding for subject
    //if it needs
//todo dbase store only message id
    //
//todo  send to dbase
    //some part may be encoded
//todo load a lot of mails by shell curl
//todo classes ?
echo time(),"<br> start<br>";

require_once ('dbase.php');
//list of emails
require_once ('config.php');

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
echo time(),"<br> connect to yandex <br>";
$resource =imap_open('{imap.yandex.ru:993/imap/ssl}INBOX','ifconfig26','ifconfig2600');//'speedcore222@yandex.ru','speedcore');
$list = imap_list($resource, '{imap.yandex.ru:993/imap/ssl}', '*');
$urgent=imap_search($resource,'FLAGGED',SE_UID);//list of flagged messages
$tempCount=count($urgent);
$db_con->multi_query('set character_set_client="utf8"');
$mNumber=[];
echo time(),"<br> mail received   and tempcount is $tempCount <br>";
for ($i=0;$i<$tempCount;$i++){
    //get id of mail message
    $uid=($urgent[$i]);
    $mNumber[$i]=imap_msgno($resource,$urgent[$i]);
    $message_head=imap_header($resource,$mNumber[$i]);
    $eSender= $message_head->senderaddress;
    //cleaning sender name
    $eSender=preg_replace('/.*?</','', $eSender);
    $eSender=(str_replace('>','',$eSender));
    $eDate=strtotime($message_head->date);//storing in UNIX time
    $eHeader=$message_head->subject;
    $eBody=imap_body($resource,$mNumber[$i]);
    preg_match('/Encoding:\ quoted-printable/', $eBody, $Rprint);
    preg_match('/base64/', $eBody, $Rbase);
    //recoding
    if (isset($Rprint[0])){
            $eBody=imap_qprint(imap_body($resource,$mNumber[$i]));
            $trigger=NULL;
         }
    elseif (isset($Rbase[0])) {
          $eBody=str_replace('Content-Transfer-Encoding: base64','',$eBody);
          $eBody= imap_base64($eBody);
        }
    // we have 3 encoding so else is NEED
    else { //just do not change $eBody;
        }
    $eHeader=$db_con->real_escape_string($eHeader);
    $eBody=$db_con->real_escape_string($eBody);
    $uid=$db_con->real_escape_string($uid);
    $eSender=$db_con->real_escape_string($eSender);

    $query="insert into email1(edate,header,sender,message,uid) values ('"
    .$eDate."','"
    .$eHeader."','"
    .$eSender."','"
    .$eBody."','"
    .$uid."')";

    $db_con->multi_query($query);
    echo mysqli_error($db_con);
    echo time(),"<br> dbase $i <br>";
}


imap_close($resource);





?>
