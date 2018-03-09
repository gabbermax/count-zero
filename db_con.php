<?php
class db_con{
    public $host='localhost';
    public $user='mailer';
    public $pass='mailer';
    public $dname='mailer';

    public function connect() {

        $this->db = new mysqli($this->host, $this->user, $this->pass, $this->dname);
        if ($this->db->connect_error) {
//			die('Connect Error: ' . $this->db->connect_error);
            die('Connect Error to MySQL');
        }
    }
}

/*$query='insert into
    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)
     values (\'1520338944\',\'else mail\',\'speedcore222@yandex.ru\',\'620\',\' \',\' \',\' \',\' \',\' \',\'1531\'
    )';
$z=$db_con->prepare($query);
insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1518510692\',\'Re: почта\',\'vasico504@gmail.com\',\'604\',\' \',\' \',\'A\',\' \',\' \',\'58029\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1519656485\',\'=?UTF-8?Q?[DigitalOcean]_Welcome_to_=E2=80=9CIn?= =?UTF-8?Q?frastructure_as_a_Newsletter=E2=80=9D?=\',\'newsletter@news.digitalocean.com\',\'613\',\' \',\' \',\' \',\' \',\' \',\'17341\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1519662956\',\'Re:temp\',\'gabbermax@hotbox.ru\',\'614\',\' \',\' \',\' \',\' \',\' \',\'2656\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1519666985\',\'test\',\'gabbermax2600@yandex.ru\',\'615\',\' \',\' \',\' \',\' \',\' \',\'2310\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1519812096\',\'New Post in Blog\',\'admin@notify.vk.com\',\'616\',\' \',\' \',\' \',\' \',\' \',\'5784\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1519921898\',\'Infrastructure as a Newsletter - March 1, 2018\',\'newsletter@news.digitalocean.com\',\'617\',\' \',\' \',\' \',\' \',\' \',\'29611\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1520076130\',\'Re: Макс с сатсанг\',\'ladnoe@list.ru\',\'618\',\' \',\' \',\'A\',\' \',\' \',\'6777\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1520337798\',\'test\',\'speedcore222@yandex.ru\',\'619\',\' \',\' \',\' \',\' \',\' \',\'1529\'\n    );insert into \n    email1(edate,header,sender,uid,flagged,recent,answered,deleted,draft,size)\n     values (\'1520338944\',\'else mail\',\'speedcore222@yandex.ru\',\'620\',\' \',\' \',\' \',\' \',\' \',\'1531\'\n    );
echo "Не удалось подготовить запрос: (" . $db_con->errno . ") " . $db_con->error;
var_dump($z);*/ ?>