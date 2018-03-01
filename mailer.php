<?php
//todo timestamp in sql
    //ready
//todo brocken encoding
    //2 types of encoding ,looks works
//todo encoding for subject
//
//todo dbase store only message id


//todo  send to dbase
    //some part may be encoded

//todo heavy

echo time(),"<br> start<br>";
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
echo time(),"<br> connect to yandex <br>";

$resource =imap_open('{imap.yandex.ru:993/imap/ssl}INBOX','ifconfig26','ifconfig2600');//'speedcore222@yandex.ru','speedcore');

$list = imap_list($resource, '{imap.yandex.ru:993/imap/ssl}', '*');

$urgent=imap_search($resource,'FLAGGED',SE_UID);
//there lot of flagger messases
$tempCount=count($urgent);
$db_con->multi_query('set character_set_client="utf8"');
$mNumber=[];

//echo time(),"<br> mail received   and tempcount is $tempCount <br>";
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
    if (isset($Rprint[0])){
            $eBody=imap_qprint(imap_body($resource,$mNumber[$i]));
            $trigger=NULL;
         }
    elseif (isset($Rbase[0])) {
          $eBody=str_replace('Content-Transfer-Encoding: base64','',$eBody);
          $eBody= imap_base64($eBody);
    }
    // we have 3 encoding so else is NEED
    else {
        //just do not change $eBody;
    }

    //echo time(),"<br> $eBody $i <br>";
    //$query='insert into email1 (uid,header,message,edate,sender) values (616,New Post in Blog,\r\n--b1_0516e7747b4e4f44eaed2876a962de0b\r\nContent-Type: text/plain; charset = \\"UTF - 8\\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nBlog\r\n      \r\n    \r\n    \r\n    \r\n\r\n  Money Transfers in Group Chats\r\n  \r\n    February 28, 2018 at 11:59 \r\n    &bull;\r\n    Юрий Иванов, Director of E-Commerce\r\n  \r\n  \r\n  \r\n    \r\n  \r\n\r\n  A great way to chip in for gifts and purchases.\r\n  Read more »\r\n\r\n\r\n\r\n    \r\n      VK © 2018\r\n      unsubscribe from emails\r\n\r\n\r\n--b1_0516e7747b4e4f44eaed2876a962de0b\r\nContent-Type: text/html; charset = \\"UTF - 8\\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n<!DOCTYPE html PUBLIC \\" -//W3C//DTD XHTML 1.0 Strict//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\\">\r\n<html>\r\n<head>\r\n  <meta http-equiv=\\"Content-Type\\" content=\\"text/html; charset=utf-8\\">\r\n  <title>New Post in Blog</title>\r\n</head>\r\n<body>\r\n  <style type=\\"text/css\\">\r\n  a { text-decoration: none; }\r\n  a:hover { text-decoration: underline; }\r\n  </style>\r\n  <div style=\\"margin:0px auto;padding: 23px 10px;max-width: 800px;font-weight: normal;font-family: \'Helvetica Neue\', Helvetica, sans-serif, Geneva, arial, Tahoma, verdana;letter-spacing:-0.1px;\\">\r\n    <a href=\\"https://vk.com/blog?from_email=1\\" style=\\"text-decoration:none;\\">\r\n      <div style=\\"width:32px;height:32px;float:left;background-color:#597da3;border-radius:3px;\\">\r\n        <img src=\\"https://vk.com/images/blog/m_about_logo.png\\" style=\\"margin-top: 10px;width: 22px;height: 13px;margin-left: 5px;\\">\r\n      </div>\r\n      <div style=\\"color:#4a6f97;font-size:14px;float:left;font-weight:bold;margin-left:10px;line-height:24px;letter-spacing:0.2px;margin-top: 5px;\\">Blog</div>\r\n      <div style=\\"clear:both;\\"></div>\r\n    </a>\r\n    <div style=\\"width:100%;height:1px;background:#e5e5e5;margin-top:20px;\\"></div>\r\n    \r\n<div>\r\n  <div style=\\"font-size: 24px;line-height:28px;font-weight:bold;margin-top:24px;\\"><a href=\\"https://vk.com/blog/moneyrequest?from_email=1\\" style=\\"color: #000;text-decoration:none;\\">Money Transfers in Group Chats</a></div>\r\n  <div style=\\"font-size: 12px;letter-spacing: 0.2px;color: #65686c;line-height:14px;margin-top:7px;\\">\r\n    <span>February 28, 2018 at 11:59 </span>\r\n    <span style=\\"color:#65686c;padding: 0px 5px;\\">&bull;</span>\r\n    <span>Юрий Иванов, Director of E-Commerce</span>\r\n  </div>\r\n  <a href=\\"https://vk.com/blog/moneyrequest?from_email=1\\" style=\\"color: #000;text-decoration:none;\\">\r\n  <div style=\\"text-align:center;margin-top:21px;padding-bottom:3px;\\">\r\n    <img src=\\"https://pp.userapi.com/c831109/v831109040/96e1d/x9igAeiZnRE.jpg\\" style=\\"max-width:100%;\\">\r\n  </div>\r\n</a>\r\n  <div style=\\"font-size: 15px;line-height: 22.5px;letter-spacing: 0.1px;margin-top:20px;color:#000;\\">A great way to chip in for gifts and purchases.</div>\r\n  <div style=\\"font-size: 14px;line-height: 16px;letter-spacing: 0.2px;margin-top:13px;font-weight:bold;\\"><a href=\\"https://vk.com/blog/moneyrequest?from_email=1\\" style=\\"color: #42648b;text-decoration:none;\\">Read more »</a></div>\r\n</div>\r\n<div style=\\"clear:both;\\"></div>\r\n<div style=\\"width:100%;height:1px;background:#e5e5e5;margin-top:21px;\\"></div>\r\n    <div style=\\"margin-top:20px;\\">\r\n      <div style=\\"font-size: 12px;letter-spacing: 0.2px;color: #7c7f82;float:left;\\">VK © 2018</div>\r\n      <a href=\\"https://vk.com/blog?from_email=1\\" style=\\"text-decoration:none;\\"><div style=\\"font-size: 12px;letter-spacing: 0.2px;color: #7c7f82;float:right;\\">unsubscribe from emails</div></a>\r\n      <div style=\\"clear:both;\\"></div>\r\n    </div>\r\n  </div>\r\n</body>\r\n</html>\r\n\r\n\r\n\r\n--b1_0516e7747b4e4f44eaed2876a962de0b--\r\n\r\n\r\n,1519812096,admin@notify.vk.com)';
    //$query="insert into email1 (uid,header) VALUES (1111,\"qwerty\")";
    $eHeader=$db_con->real_escape_string($eHeader);
    $eBody=$db_con->real_escape_string($eBody);
    $uid=$db_con->real_escape_string($uid);
    $eSender=$db_con->real_escape_string($eSender);
   // $eHeader,$eBody,$eDate,$eSender)
    //$query="insert into email1(uid,header,edate,sender) values ($uid,$eHeader,$eDate,$eSender)";
    //real_escape_string
     //$db_send=$db_con->prepare($query);
    //$query="insert into email1 (uid,header) VALUES (1111,'".$eSender."')";

    $query="insert into email1(edate,header,sender,message,uid) values ('"
    .$eDate."','"
    .$eHeader."','"
    .$eSender."','"
    .$eBody."','"
    .$uid."')";
    
    $temper= htmlspecialchars( $eSender);
    $temper2=$db_con->real_escape_string('"qwerty"');
    $db_con->multi_query($query);
   echo mysqli_error($db_con);
    echo time(),"<br> dbase $i <br>";
}



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





?>
