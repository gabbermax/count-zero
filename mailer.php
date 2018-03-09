<?php
spl_autoload_register('myAutoloader');

function myAutoloader($className)
{
    $path = 'classes/'.$className.'.php';


    return include $path;

}

$resource   = imap_open('{imap.yandex.ru:993/imap/ssl}INBOX','*','*');
$list       = imap_list($resource, '{imap.yandex.ru:993/imap/ssl}', '*');
//$imap_check = imap_check($resource);
$imap_head  = imap_headerinfo($resource,1);
$Num_mess   = imap_num_msg($resource);
//todo пробовать на другой системе с wireshark  , по прежнему без результата
$alerts     = imap_alerts();
$urgent     = imap_search($resource,'FLAGGED',SE_UID);//list of flagged messages
$tempCount  = count($urgent);
$query='';
$mNumber = [];
$MC          = imap_check($resource);
$allMessages = imap_fetch_overview($resource,"1:{$MC->Nmsgs}",0);
$kolvoMess   = count($allMessages);

//
for ($i=0;$i<$kolvoMess;$i++){

    $uid          = ($allMessages[$i]->uid);
    $mNumber[$i]  = imap_msgno($resource,$uid);
    $message_head = imap_header($resource,$mNumber[$i]);
    $eSender      = $message_head->senderaddress;
    $eflags       = $message_head->Flagged;
    $erecent      = $message_head->Recent;
    $eAnswered    = $message_head->Answered;
    $eDeleted     = $message_head->Deleted;
    $eDraft       = $message_head->Draft;
    $eSize        = $message_head->Size;
    $eSender      = preg_replace('/.*?</','', $eSender);
    $eSender      = preg_replace('/.*?</','', $eSender);
    $eSender      = (str_replace('>','',$eSender));
    $eDate        = strtotime($message_head->date);//storing in UNIX time
    $eHeader      = $message_head->subject;
    $is_base64    = substr_count($eHeader,'=?UTF-8?B?');

    if($is_base64){
        $eHeader = preg_replace('/=\?UTF\-8\?B\?/','', $eHeader);
        $eHeader = preg_replace('/\?=/','', $eHeader);
        $eHeader = imap_base64($eHeader);
    }

     $query1="insert into 
     email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)
     values ('"
    .$eDate."','"
    .$eHeader."','"
    .$eSender."','"
    .$uid."','"
    .$eflags."','"
    .$erecent."','"
    .$eAnswered."','"
    .$eDeleted."','"
    .$eDraft."','"
    .$eSize."'
    )";
    //делаем очередь из писем
    $query=$query.$query1.";";

}
$db_con2=new db_con;
$db_con2->connect();
//это функция mysqli , она не устаревшая ,prepare возвращает false
$query=$db_con2->db->real_escape_string($query);
$db_con2->db->multi_query($query);


imap_close($resource);





?>
