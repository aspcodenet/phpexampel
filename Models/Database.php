<?php
require_once('Models/Customer.php');
require_once('Models/Office.php');
require_once('Models/UserDatabase.php');
require_once('Migrations/migrationBase.php');


class DBContext{


    private $pdo;
    private $usersDatabase;

    function getUsersDatabase(){
        return $this->usersDatabase;
    }
        
    function __construct() {    
        $host = $_ENV['host'];
        $db   = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];
        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->usersDatabase = new UserDatabase($this->pdo);
        $this->initIfNotInitialized();
        $this->seedfNotSeeded();    
    }

    function getAllOffices(){
        return $this->pdo->query('SELECT * FROM Office')->fetchAll(PDO::FETCH_CLASS, 'Office');
        
    }


    function getPopularCustomers(){
        return $this->pdo->query('select * from Customer order by Popularity desc limit 0,10')->fetchAll(PDO::FETCH_CLASS, 'Customer');
        
    }

    
    function searchCustomers($sortCol, $sortOrder, $q,$categoryId, $pageNo = 1, $pageSize = 20){
        if($sortCol == null){
            $sortCol = "Id";
        }
        if($sortOrder == null){
            $sortOrder = "asc";
        }
        $sql = "SELECT * FROM Customer ";
        $paramsArray = [];
        $addedWhere = false;
        if($q != null && strlen($q) > 0){  // Omman angett ett q - WHERE   tef
                // select * from product where title like '%tef%' // Stefan  tefan atef
            if(!$addedWhere){
                $sql = $sql . " WHERE ";            
                $addedWhere = true;
            }else{
                $sql = $sql . " AND ";    
            }
            $sql = $sql . " ( NationalId like :q";        
            $sql = $sql . " OR  GivenName like :q";        
            $sql = $sql . " OR  Surname like :q";        
            $sql = $sql . " OR  City like :q )";        
            $paramsArray["q"] = '%' . $q . '%';                
        }
        if($categoryId != null && strlen($categoryId) > 0){
            if(!$addedWhere){
                $sql = $sql . " WHERE ";            
                $addedWhere = true;
            }else{
                $sql = $sql . " AND ";    
            }
            $sql = $sql . " ( OfficeId = :categoryId )";        
            $paramsArray["categoryId"] = $categoryId;                
        }

        
        $sql .= " ORDER BY $sortCol $sortOrder ";    

        $sqlCount = str_replace("SELECT * FROM ", "SELECT CEIL (COUNT(*)/$pageSize) FROM ", $sql);

        // $pageNo = 1, $pageSize = 20
        $offset = ($pageNo-1)*$pageSize;
        $sql .= " limit $offset, $pageSize";    

        $prep = $this->pdo->prepare($sql);
        $prep->setFetchMode(PDO::FETCH_CLASS,'Customer');
        $prep->execute($paramsArray);
        $data = $prep->fetchAll();      // arrayen  



        $prep2 = $this->pdo->prepare($sqlCount);
        $prep2->execute($paramsArray);

        $num_pages = $prep2->fetchColumn();       // antal sidor tex 3      

         $arr =  ["data"=>$data, "num_pages"=>$num_pages];
         return $arr;

    }

 function getCustomer($id){
        $prep = $this->pdo->prepare('SELECT * FROM Customer where id=:id');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Customer');
        $prep->execute(['id'=> $id]);
        return  $prep->fetch();
    }
    function getCustomerByNames($GivenName,$Surname){
        $prep = $this->pdo->prepare('SELECT * FROM Customer where GivenName=:givenname and Surname=:surname');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Customer');
        $prep->execute(['givenname'=> $GivenName, 'surname' => $Surname]); 
        return  $prep->fetch();
    }

    function getOfficeByName($title) {
        $prep = $this->pdo->prepare('SELECT * FROM Office where name=:title');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Office');
        $prep->execute(['title'=> $title]); 
        return  $prep->fetch();
    }


    function getOffice($id) {
        $prep = $this->pdo->prepare('SELECT * FROM Office where id=:id');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Office');
        $prep->execute(['id'=> $id]); 
        return  $prep->fetch();
    }




    function seedfNotSeeded(){
        static $seeded = false;
        if($seeded) return;

        $offices = $this->getAllOffices();
        
        $this->createIfNotExisting('Julianna','Castle','3759 Cordova Lane','Ypsilanti','48197','USA','US','1974-12-17 00:00:00','19741217-3496',55,'(971)360-3873','lindy@eaqui.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Milo','Land','8958 Laurie Parkway','Chicago Heights','60412','USA','US','1943-04-02 00:00:00','19430402-4486',55,'(571)598-7757','i.hite@doloreum.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Augustine','Serna','8694 Sandy Port Way','Knoxville','37940','USA','US','1982-08-12 00:00:00','19820812-3406',55,'(989)588-5810','roxann.nowak@ipsumcommodoconsequat.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Gayle','Courtney','9760 Keystone Drive','Manchester','37349','USA','US','1984-12-26 00:00:00','19841226-6011',55,'(980)777-7087','eileen@nullaadipiscing.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tommie','Barrows','9451 Dove Field Circle','Washington','20076','USA','US','1996-11-11 00:00:00','19961111-3705',55,'(513)681-4320','dona@nislinvulputate.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Young','Novak','2365 Cedarwood Trail','Elizabethtown','28337','USA','US','1969-12-29 00:00:00','19691229-3415',55,'(773)164-6855','h.hudson@feugaitconsequat.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Kelvin','Grover','4428 Cottage Glen Cove','Pleasantville','50225','USA','US','1956-10-20 00:00:00','19561020-4916',55,'(267)650-5701','t.castle@velitfacilisis.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Brandie','Kiefer','2216 Grovewood Way','Flat Rock','28731','USA','US','1973-04-16 00:00:00','19730416-6011',55,'(207)669-8344','emanuel.roque@enimet.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Vern','Bradbury','5305 Oldridge Avenue','Ridgeville Corners','43555','USA','US','1944-09-07 00:00:00','19440907-4929',55,'(570)983-8348','mickey@essenulla.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Kasey','Richard','1035 Wellton Circle','Gurley','35748','USA','US','1973-12-02 00:00:00','19731202-4916',55,'(510)864-9127','m.thames@ipsumconsequatvel.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Coy','Brink','2706 Saddlegait Lane','Hampton','29924','USA','US','1965-04-27 00:00:00','19650427-5560',55,'(864)520-6175','claire@delenitauguezzril.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Zachariah','Gilmer','2814 Centre Oak Parkway','Novi','48377','USA','US','1967-12-03 00:00:00','19671203-6011',55,'(513)528-4638','hal@eratnibh.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Phillip','Fry','1807 Saddlegait Street','Metamora','43540','USA','US','2003-02-06 00:00:00','20030206-5228',55,'(781)903-9182','ona.jacques@veniamquisvero.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Clyde','Bowlin','1538 Cane Creek Circle','Cripple Creek','80813','USA','US','1952-10-17 00:00:00','19521017-4916',55,'(973)767-7422','genoveva@diameuismod.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Floy','Keeton','8186 Brewers Trail','Greensburg','15601','USA','US','1961-04-09 00:00:00','19610409-6011',55,'(786)526-8744','claudio.barnard@delenitauguenostrud.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Majorie','Haugen','9372 Queensbury Street','Loughman','33858','USA','US','2003-01-18 00:00:00','20030118-5437',55,'(414)236-9907','m.creech@duismolestie.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Xavier','Hoffmann','5154 Wynterhall Lane','Stone Mountain','30087','USA','US','1952-04-28 00:00:00','19520428-6011',55,'(570)684-9198','jana.falls@illumconsequatvel.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Belinda','Elmore','2314 Forest Downs Trail','West Bloomfield','48323','USA','US','1970-08-05 00:00:00','19700805-5351',55,'(228)805-4119','sharla.ramirez@utin.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Santo','Wallace','4298 Stamford Parkway','Oasis','84650','USA','US','1962-02-13 00:00:00','19620213-5592',55,'(207)310-3754','scottie.mayhew@exdolor.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Roderick','Mccorkle','1311 Wickshire Circle','Tacoma','98433','USA','US','1979-03-21 00:00:00','19790321-3779',55,'(667)281-6370','v.williams@nulladiam.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Winnie','Uribe','7637 Wilkins Trail','Harrisburg','17102','USA','US','1966-01-16 00:00:00','19660116-4929',55,'(706)398-5597','lesley@facilisiexerci.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marc','Lessard','5808 Crooked Oak Drive','Pax','25904','USA','US','1942-03-02 00:00:00','19420302-6011',55,'(947)296-8289','a.soto@wisiduis.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Angelica','Beattie','5250 Ilo Lane','Sarita','78385','USA','US','1979-02-12 00:00:00','19790212-3441',55,'(208)670-6745','billy.mcdougall@suscipitpraesent.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Clint','Whittaker','3718 Omar Lane','Hardin','59034','USA','US','1952-08-15 00:00:00','19520815-5301',55,'(501)892-2011','marlys.forsyth@dignissimillum.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Chanel','Chew','1026 Wolf River Way','Wilson','27895','USA','US','1978-07-24 00:00:00','19780724-6011',55,'(557)297-9871','melvin.brandt@illummolestie.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Nathanial','Tran','8864 Beaux Bridge Parkway','Tunnel City','54662','USA','US','1942-12-13 00:00:00','19421213-6011',55,'(785)937-6717','giuseppe@facilisicommodoconsequat.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Gayla','Frederick','7978 Circleshade Cove','Seth','25181','USA','US','1944-01-28 00:00:00','19440128-5430',55,'(464)662-4552','o.russ@invulputateeros.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Elva','Scales','1888 Magnolia Tree Avenue','White River','57579','USA','US','1994-06-25 00:00:00','19940625-6011',55,'(870)115-2299','jeanna@nonummynislut.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Jason','Benner','4077 Dubuque Avenue','Ravenna','44266','USA','US','2002-08-25 00:00:00','20020825-4486',55,'(571)249-4978','d.billiot@erosut.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Mel','Story','2085 Silverbark Avenue','Nevis','56467','USA','US','1960-12-04 00:00:00','19601204-6011',55,'(862)173-3376','j.speight@nulladolore.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Ofelia','Blunt','2509 Circle Trees Cove','Homeland','92548','USA','US','1952-10-19 00:00:00','19521019-3724',55,'(214)815-5364','phillip@utwisiat.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marcelino','Chow','4018 Cedarcrest Drive','Terre Hill','17581','USA','US','2000-11-26 00:00:00','20001126-4929',55,'(763)338-6851','natasha.reeder@ineum.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Trinidad','Lozano','2651 Greensprings Way','Manchester','73758','USA','US','1975-04-30 00:00:00','19750430-3480',55,'(314)282-2324','antwan.coats@hendreritillum.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Iola','Surratt','9222 Grisby Circle','Randolph','4346','USA','US','1950-04-01 00:00:00','19500401-5149',55,'(816)156-2466','clara@suscipiteum.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Aline','Swope','3974 Londonderry Street','Columbus','43222','USA','US','1982-03-03 00:00:00','19820303-5129',55,'(564)546-4933','w.crum@aliquipnislut.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Eloise','Scarborough','8828 Village Shops Trail','Platina','96076','USA','US','1990-06-11 00:00:00','19900611-3712',55,'(605)395-5122','sofia.jean@euvel.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Alexander','Conrad','3042 Splinter Oak Parkway','Norfolk','23504','USA','US','1996-04-20 00:00:00','19960420-3452',55,'(702)243-3030','tina.strand@dolorenostrud.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Bret','Grogan','9789 Shelton Trail','San Diego','92114','USA','US','1966-08-25 00:00:00','19660825-5172',55,'(330)779-4666','rusty@duisut.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Shayne','Woody','4663 Barryknoll Parkway','Cresson','16699','USA','US','2002-12-17 00:00:00','20021217-3448',55,'(360)522-9218','lucile@laoreetduis.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lorraine','Belton','1105 Rivard Parkway','Almo','83312','USA','US','1985-01-15 00:00:00','19850115-6011',55,'(804)815-3874','francis@hendreritnulla.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Pedro','Morin','3775 Addicks Howell Way','Toccoa','30577','USA','US','1945-04-02 00:00:00','19450402-3444',55,'(541)590-7074','brianna@involutpat.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Darrell','Hallman','59 Long Lake Lane','Trementina','88439','USA','US','1988-03-04 00:00:00','19880304-3799',55,'(217)789-6773','josefina@minimhendrerit.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Chance','Eads','8266 Buckingham Trail','Charleston','29423','USA','US','1972-05-05 00:00:00','19720505-3790',55,'(914)582-8192','marianne@quinostrud.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Walter','Ayres','7504 Deer Way','Forest Hill','21050','USA','US','1977-11-06 00:00:00','19771106-3789',55,'(850)851-3646','s.hager@eumduis.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Mary','Schafer','9481 Ravenhill Trail','Saint Louis','63137','USA','US','1944-01-30 00:00:00','19440130-3470',55,'(712)552-6315','rueben@praesenthendrerit.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Verla','Bourne','9866 Queens Cove','Oilton','78371','USA','US','1972-08-13 00:00:00','19720813-6011',55,'(615)937-1592','pasquale.fournier@molestiedolor.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Elisa','Joyce','2147 Rustleaf Drive','Amityville','11708','USA','US','1986-01-24 00:00:00','19860124-5416',55,'(878)447-2008','laurence.stone@utad.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Dixie','Mason','5299 Frontage Street','Dolgeville','13329','USA','US','1961-12-18 00:00:00','19611218-4294',55,'(320)242-5732','mariana.craig@dolorelobortis.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Jordon','Hatfield','4519 Oakgreen Avenue Parkway','Houghton Lake','48629','USA','US','1948-10-02 00:00:00','19481002-5249',55,'(878)233-1787','deirdre.smothers@atduis.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Wade','Mccaskill','9396 Woodlane Lane','Beulaville','28518','USA','US','1966-12-23 00:00:00','19661223-5563',55,'(505)865-6127','fabian.dewitt@molestiepraesent.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Karol','Mccutcheon','4047 Poplar Lake Cove','Stevinson','95374','USA','US','1970-06-17 00:00:00','19700617-4539',55,'(605)932-2557','gabrielle@diamesse.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Carlotta','Flint','6996 Rich Hill Street','New York','10211','USA','US','1960-05-23 00:00:00','19600523-3462',55,'(817)118-5004','christian.mcdermott@nullasuscipit.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marion','Sneed','6484 Paddock Drive','Lone Star','75668','USA','US','1986-08-20 00:00:00','19860820-5508',55,'(559)559-8652','stefan@exercieufeugiat.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Sebastian','Storm','2867 Rustleaf Parkway','Barren Springs','24313','USA','US','1953-09-27 00:00:00','19530927-5250',55,'(754)266-1658','cristopher.puckett@hendreritduis.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Brianna','Rosado','6582 Barkers Landing Trail','Dryden','78851','USA','US','2003-11-20 00:00:00','20031120-3763',55,'(240)554-9034','mickey.mabe@nullavelit.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Leona','Mccullough','2877 Waverly Parkway','Aurora','80041','USA','US','1986-09-21 00:00:00','19860921-3786',55,'(301)323-9655','aretha@suscipitnulla.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tod','Hamel','4359 Dragonfly Way','Midway','75852','USA','US','1987-03-11 00:00:00','19870311-3459',55,'(740)431-5104','fermin.beale@laoreetautem.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Valentin','Childress','2665 Chadbourne Trail','Irving','14081','USA','US','1975-08-11 00:00:00','19750811-6011',55,'(715)905-6514','e.falls@eratsuscipit.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Vito','Fawcett','7881 Thornbranch Trail','Wilmington','19801','USA','US','1986-03-06 00:00:00','19860306-3755',55,'(908)218-3143','santos@dolorecommodoconsequat.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tod','Harter','4366 Wheatley Drive','Mansfield','30055','USA','US','2002-06-26 00:00:00','20020626-6011',55,'(361)753-7553','rodger@loremfeugait.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Alissa','Hoskins','5568 Water Street','Gastonia','28054','USA','US','1997-11-02 00:00:00','19971102-5261',55,'(919)415-3007','j.sowers@inautem.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Del','Banner','8234 Regents Lane','Hewett','25108','USA','US','1988-08-15 00:00:00','19880815-5379',55,'(202)521-1542','ramiro.conger@consequatmolestie.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Valencia','Chin','7746 Perthshire Lane','Palestine','75801','USA','US','1983-05-22 00:00:00','19830522-6011',55,'(208)147-5411','earnestine.provost@volutpatnulla.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Rafael','Singh','373 Nightingale Parkway','Hanover','53542','USA','US','1987-10-24 00:00:00','19871024-4888',55,'(386)172-7145','jacob@veniamut.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Iona','Haines','466 Capital Lane','Lincoln','68527','USA','US','1987-06-26 00:00:00','19870626-6011',55,'(217)698-7299','rueben@etdolor.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tera','Evers','3060 Tanoak Circle','Kaukauna','54130','USA','US','1975-04-30 00:00:00','19750430-3769',55,'(660)272-1600','evangeline.russ@loremet.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Herbert','Bittner','4840 Twisted Oak Drive','Blue Springs','64013','USA','US','1942-04-04 00:00:00','19420404-5140',55,'(512)949-9337','corey.fiore@etaccumsanenim.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Chadwick','Sherrod','7782 Woodhall Way','Bath','29816','USA','US','1996-12-12 00:00:00','19961212-3482',55,'(760)538-2539','preston.baptiste@doloresse.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Holli','Pounds','3368 Windbreak Cove','Stewartsville','8886','USA','US','1995-04-20 00:00:00','19950420-4556',55,'(434)293-9642','madelyn@zzrilinvulputate.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Andreas','Mares','8690 Woodbend Parkway','Pilot Grove','65276','USA','US','1995-07-22 00:00:00','19950722-5194',55,'(509)408-5751','katherine@quisdolore.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tod','Scoggins','5235 Forest Bend Street','Browning','64630','USA','US','1991-08-27 00:00:00','19910827-3757',55,'(614)494-8823','r.field@exerciminim.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Britt','Batiste','3568 Pinehurst Circle','Valparaiso','46383','USA','US','1957-12-02 00:00:00','19571202-4024',55,'(541)430-7670','d.alcorn@eafeugait.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Rhonda','Lashley','9356 Oakgreen Street','Cunningham','37052','USA','US','1980-12-28 00:00:00','19801228-5482',55,'(470)206-9644','m.whipple@duisexerci.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Delfina','Coyle','284 Splinter Oak Cove','Jacksonville','32227','USA','US','2003-01-04 00:00:00','20030104-4716',55,'(580)250-7435','r.leahy@nisliusto.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Alexander','Folse','3101 Tending Trail','Mantorville','55955','USA','US','1982-08-30 00:00:00','19820830-6011',55,'(260)898-2681','johnnie@inut.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Cristobal','Ahrens','9274 Brookside Trail','Bloomington','83223','USA','US','1988-06-02 00:00:00','19880602-6011',55,'(858)824-8230','b.watt@euenim.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Mariam','Tang','318 Heatherly Cove','Burton','48509','USA','US','2001-01-30 00:00:00','20010130-4486',55,'(847)128-1909','hal@nullaodio.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Felix','Shank','142 Overhill Cove','Dearborn','48121','USA','US','1956-05-24 00:00:00','19560524-5381',55,'(541)456-3611','iola@odionulla.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Logan','Lowe','4959 Foster Ridge Cove','Louisville','40270','USA','US','1987-06-17 00:00:00','19870617-6011',55,'(920)569-1479','sheri.christman@blanditqui.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Kerry','Bingham','636 Westcott Cove','Indianapolis','46278','USA','US','1943-05-31 00:00:00','19430531-6011',55,'(918)961-5028','annmarie@diaminvulputate.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Robbie','Christensen','7481 Still Meadow Circle','Watkinsville','30677','USA','US','1994-04-17 00:00:00','19940417-3738',55,'(925)824-2543','mariam.payne@odioeum.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marquis','Bergstrom','9441 Locust Trail','Drury','65638','USA','US','1956-09-16 00:00:00','19560916-4556',55,'(229)879-7710','b.arnett@veroeu.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Anita','Coughlin','5192 Oak Park Lane','Midland City','36350','USA','US','1975-02-28 00:00:00','19750228-4539',55,'(212)683-6261','lizzie.vaughn@duisconsequat.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Sherri','Hanley','3788 Hidden Trail','Paint Bank','24131','USA','US','1956-12-25 00:00:00','19561225-5300',55,'(763)704-4630','deloris@eufeugiatiriure.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lakeisha','Felton','96 Nelson Trail','Baraboo','53913','USA','US','2001-05-23 00:00:00','20010523-5387',55,'(206)864-5022','simone.joyce@luptatumzzrilconsequatvel.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Ezekiel','Parrott','7023 Palisade Avenue','Central','99730','USA','US','1948-03-16 00:00:00','19480316-4929',55,'(231)608-7777','emilie.murdock@velblandit.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Juliet','Landon','8176 Corporate Gardens Trail','Dunn','28334','USA','US','1943-08-31 00:00:00','19430831-6011',55,'(641)230-5379','m.spence@illumsuscipit.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tamika','Earls','4385 Van Tassel Lane','Hackettstown','7840','USA','US','1992-08-04 00:00:00','19920804-4716',55,'(318)366-5762','casandra@utillum.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Mae','Keene','4901 Lakemere Avenue','Greenville','27836','USA','US','1964-11-17 00:00:00','19641117-6011',55,'(270)945-7818','m.richey@veniamquiset.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Dirk','Centeno','214 Oakes Trail','Pensacola','32508','USA','US','1942-08-23 00:00:00','19420823-6011',55,'(307)842-2040','alison.chang@erosnostrud.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Hung','Villegas','2053 Thicket Way','Los Altos','94024','USA','US','1976-08-26 00:00:00','19760826-5107',55,'(425)915-2862','andrea.batts@elitsedhendrerit.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('May','Lindstrom','8078 Steamboat Circle','Parrottsville','37843','USA','US','1992-06-23 00:00:00','19920623-4539',55,'(339)729-8930','christopher@dolornibh.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Millard','Large','5072 Mcdougal Drive','Cumnock','27237','USA','US','1949-01-17 00:00:00','19490117-4716',55,'(571)338-2221','toney@eumblandit.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Nicky','Clancy','8065 Brookline Street','Greeneville','37745','USA','US','2001-04-14 00:00:00','20010414-6011',55,'(304)146-5590','alfonzo.coates@utillum.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Angie','Schindler','5661 Hazelton Lane','Powder Springs','37848','USA','US','1995-10-01 00:00:00','19951001-5157',55,'(701)880-3912','gregoria.hickson@wisiesse.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Alisha','Mitchell','3237 Magnolia Ridge Lane','Sweet Home','77987','USA','US','1979-06-15 00:00:00','19790615-5407',55,'(254)789-5585','lon.allman@ullamcorperutwisi.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Denis','Tate','273 Brierbrook Drive','Jackson','39204','USA','US','1992-09-28 00:00:00','19920928-6011',55,'(971)958-5810','marge@diamconsectetuer.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marina','Mccoy','816 Dogwood Crest Trail','Craig','68019','USA','US','1977-02-25 00:00:00','19770225-6011',55,'(857)751-7185','lee.underwood@illumea.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Harrison','Fullerton','4062 Washington Circle','Reading','19603','USA','US','1962-10-24 00:00:00','19621024-5302',55,'(931)993-2409','m.wharton@veniamquisaugue.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Pasquale','Wampler','7021 Jocelyn Trail','Coila','38923','USA','US','1989-01-04 00:00:00','19890104-3453',55,'(662)797-4105','fletcher@nullaet.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Kimberlee','Lowry','5246 Mendel Avenue','Bridgeton','28519','USA','US','1943-08-22 00:00:00','19430822-4805',55,'(702)287-4957','malissa@ullamcorperet.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Abdul','Campbell','9866 Elm Ridge Circle','Tracy','95304','USA','US','1980-06-20 00:00:00','19800620-3479',55,'(309)183-2913','omer@invulputateautem.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Denice','Shrader','3024 Wyndhurst Cove','Bonnyman','41719','USA','US','1971-06-23 00:00:00','19710623-3416',55,'(775)224-7057','tracey.pool@blanditelitsed.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Katherine','Olive','7374 Bolling Brook Avenue','Folsom','88419','USA','US','1954-04-24 00:00:00','19540424-3753',55,'(870)786-8108','t.staten@minimnulla.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Carmelo','Appleton','374 William Circle','Irwinville','31760','USA','US','1961-04-25 00:00:00','19610425-3739',55,'(812)195-1567','joann.willis@hendreritmolestie.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Max','Vandyke','9826 Oakhill Street','Whitten','50269','USA','US','1964-08-30 00:00:00','19640830-4916',55,'(660)585-4285','edythe.farrow@diamad.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Zackary','Singh','3905 Akerswood Avenue','Phoenix','85099','USA','US','1949-01-31 00:00:00','19490131-3712',55,'(610)556-5850','chadwick@indolor.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Milan','Ochs','5199 Hacks Cross Trail','Cozad','69130','USA','US','2000-10-13 00:00:00','20001013-4539',55,'(219)389-8118','spencer.new@doloretationullamcorper.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Madeline','Atkins','819 Cordova Lane','Louisville','40283','USA','US','1997-08-11 00:00:00','19970811-4929',55,'(979)356-1160','dominic.staton@elitsedcommodo.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lesley','Corral','5517 Belfort Street','Wawaka','46794','USA','US','1963-01-30 00:00:00','19630130-3732',55,'(843)402-9983','lenny.nemeth@exdelenitaugue.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Clement','Shackelford','6445 Northridge Circle','Roslyn Heights','11577','USA','US','1982-08-08 00:00:00','19820808-5421',55,'(623)743-8716','heidi.vogel@esselobortis.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Cindi','Funk','7346 Jamaca Lane','Waunakee','53597','USA','US','1981-12-26 00:00:00','19811226-5391',55,'(918)267-9450','l.hembree@sitdolore.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Earnest','Vest','8923 Great Oaks Way','Cumberland','21501','USA','US','1958-06-13 00:00:00','19580613-6011',55,'(276)545-4596','s.sprouse@consequatvelvolutpat.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Jamar','Bowling','2818 Lasso Avenue','Springfield','62722','USA','US','1956-01-10 00:00:00','19560110-4532',55,'(561)853-9822','lucien.dunham@nonummylobortis.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Ethan','Britton','1669 Wren Circle','Blue Hill','68930','USA','US','2003-09-17 00:00:00','20030917-4385',55,'(406)975-4607','f.south@hendrerithendrerit.edu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Shelly','Thorn','3230 Delano Trail','Hialeah','33016','USA','US','1954-08-20 00:00:00','19540820-5404',55,'(314)187-9513','rosalyn.timm@exnulla.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Brandi','Woodward','9161 Jenna Cove','Landisburg','17040','USA','US','1957-07-16 00:00:00','19570716-4539',55,'(507)284-3657','lanny.lofton@molestienostrud.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Jerrell','Rollins','8921 Woodford Lane','Buford','30515','USA','US','1992-03-03 00:00:00','19920303-4716',55,'(501)630-3219','berniece@nostrudiusto.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Jake','Headley','4365 Grand Oak Way','Woodgate','13494','USA','US','1960-05-07 00:00:00','19600507-6011',55,'(626)345-6808','josh@iustoodiovelit.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lloyd','Swann','628 Schulenberg Avenue','Heidenheimer','76533','USA','US','1973-05-12 00:00:00','19730512-3773',55,'(304)386-2711','shannon.garcia@exercite.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Emanuel','Kowalski','7453 Billy Cross Avenue','Wheaton','56296','USA','US','1964-08-24 00:00:00','19640824-5152',55,'(605)753-2560','taryn@nibhvelit.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Andrea','Halstead','4035 Marywood Chase Trail','Somerset','81434','USA','US','1947-12-09 00:00:00','19471209-5500',55,'(304)515-7077','chi@teeuismod.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Monika','Redden','9914 Havershire Lane','Eagleville','19415','USA','US','1965-05-17 00:00:00','19650517-3779',55,'(712)773-2283','rosario@feugiatluptatumzzril.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Rosanna','Curry','1087 Melville Trail','Chatsworth','8019','USA','US','1944-05-10 00:00:00','19440510-5202',55,'(260)964-3744','c.crump@veliriuredolor.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Ray','Baumann','6400 Oak Bend Drive','Flushing','11381','USA','US','1948-08-10 00:00:00','19480810-6011',55,'(405)317-1378','gerard@eumnostrud.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Reed','Lash','301 Pioneer Parkway','Northbrook','60065','USA','US','1958-10-21 00:00:00','19581021-3489',55,'(918)927-5570','f.hahn@facilisisit.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Sofia','Spangler','1914 River Roads Circle','Greens Fork','47345','USA','US','1970-02-24 00:00:00','19700224-6011',55,'(847)949-9175','yesenia.thatcher@luptatumodio.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('George','Bertrand','7681 Thornbranch Lane','Oraville','62971','USA','US','1989-09-22 00:00:00','19890922-6011',55,'(601)845-7050','trinidad@doloremagnaaliquip.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Bruce','Toro','199 Belfort Cove','Erie','16550','USA','US','1954-11-02 00:00:00','19541102-6011',55,'(843)659-8795','mariano.soliz@enimwisi.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Claudia','Hughey','2367 Rich Hill Drive','Saint David','85630','USA','US','1995-01-11 00:00:00','19950111-4929',55,'(520)605-6203','belinda.binder@exullamcorper.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Patricia','Garris','4104 Manning Trail Street','Mechanicstown','44651','USA','US','1945-01-29 00:00:00','19450129-3794',55,'(925)642-4293','pilar@nislet.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Booker','Emery','3081 Kellywood Way','Gunlock','84733','USA','US','1972-02-28 00:00:00','19720228-6011',55,'(405)318-3037','w.marrero@doloresit.org',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Natasha','Pennell','5865 Brooxie Street','Ethelsville','35461','USA','US','2001-12-17 00:00:00','20011217-3494',55,'(334)495-5247','s.blocker@duisut.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Merle','Paxton','2610 Jasmine Street','Ward','29166','USA','US','1998-12-27 00:00:00','19981227-3794',55,'(731)670-7974','ola@exnislut.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lessie','Montes','5006 Neal Lane','Feeding Hills','1030','USA','US','1969-10-20 00:00:00','19691020-3735',55,'(562)556-7034','flor@nullaesse.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Marisa','Flagg','1550 Water Lane','Spring','77381','USA','US','1999-03-12 00:00:00','19990312-5460',55,'(830)208-5854','hattie.echols@accumsanea.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Les','Fajardo','4470 Kirkwood Drive','Deerfield','64741','USA','US','1965-03-03 00:00:00','19650303-4556',55,'(339)618-7131','r.burkhart@ullamcorperin.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Milton','Barbee','9168 Tower Lane','Steubenville','43953','USA','US','1969-05-11 00:00:00','19690511-4716',55,'(918)997-9126','e.bolling@duisdignissim.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Bobbie','Longo','960 Echo Parkway','Witmer','17585','USA','US','1980-07-31 00:00:00','19800731-3456',55,'(775)321-2123','augusta.lundy@duiselitsed.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Brenton','Leach','5597 Stoneleigh Lane','Rupert','83350','USA','US','1943-01-05 00:00:00','19430105-5472',55,'(508)265-7042','ellsworth.breland@addoloremagna.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Rita','Merchant','2218 Claiborne Farm Way','Wadley','36276','USA','US','1974-01-21 00:00:00','19740121-6011',55,'(304)987-7508','j.sadler@erosdiam.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Terrence','Bearden','9798 Autumn Trail','Bisbee','58317','USA','US','1974-09-23 00:00:00','19740923-4916',55,'(618)762-4993','shelli@lobortisamet.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Dwain','Peebles','9534 Buckingham Trail','Wahkiacus','98670','USA','US','1977-11-06 00:00:00','19771106-4024',55,'(225)595-7383','richie.duggan@nibhdoloremagna.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Eunice','Pierre','7656 Stafford Trail','Paulding','45879','USA','US','1983-10-13 00:00:00','19831013-5312',55,'(269)436-2654','a.ventura@lobortiseros.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Donn','Headley','1130 Old Deer Lane','Harrisonburg','22803','USA','US','1979-03-29 00:00:00','19790329-5438',55,'(916)909-6731','melvin@insuscipit.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Moshe','Bland','2975 Stone Walk Avenue','Hartford','12838','USA','US','1990-10-21 00:00:00','19901021-3780',55,'(947)921-7973','a.pittman@autemdolor.net',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Pansy','Hundley','1566 Watkins Way','Waunakee','53597','USA','US','2003-05-26 00:00:00','20030526-5132',55,'(205)682-8830','jayme@molestiein.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Beulah','Marin','1225 Norcrest Way','Tiline','42083','USA','US','1980-02-13 00:00:00','19800213-4916',55,'(601)600-6740','g.sargent@dignissimvel.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Max','Haight','7369 Stillwater Trail','Viper','41774','USA','US','1982-01-16 00:00:00','19820116-4929',55,'(651)489-1584','wiley@adconsequat.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Cruz','Thacker','1284 Stagecoach Avenue','Chloe','25235','USA','US','1956-02-13 00:00:00','19560213-5353',55,'(915)161-5958','tomasa@illumnostrud.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Sharon','Romeo','778 Ramsey Way','Saint Pauls','28384','USA','US','1968-06-14 00:00:00','19680614-5176',55,'(440)889-4962','shane.jeffrey@lobortisamet.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Ervin','Gorman','435 Rock Trail','Bronx','10461','USA','US','1987-02-22 00:00:00','19870222-5543',55,'(734)563-8195','dollie@praesenteu.info',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Carla','Paredes','8066 Old Mill Trail','Pekin','61558','USA','US','1971-12-16 00:00:00','19711216-3463',55,'(602)733-3458','lamar@dolordolor.tv',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Scarlett','Martinson','6696 Mckusick Parkway','Ipava','61441','USA','US','1945-03-08 00:00:00','19450308-4539',55,'(845)255-9987','romona.leavitt@adenim.us',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Tammie','Matlock','3320 Woodbend Lane','Veblen','57270','USA','US','1947-12-01 00:00:00','19471201-5365',55,'(484)410-5520','christy@iriuredolorcommodo.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Gregorio','Bowser','7861 Walnut Parkway','Coulters','15028','USA','US','2001-11-01 00:00:00','20011101-4532',55,'(435)805-9292','humberto@consequatte.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Francis','Fowlkes','242 Kimbrough Grove Cove','Spring Valley','45370','USA','US','1946-03-21 00:00:00','19460321-5437',55,'(276)692-3319','delois.corral@adipiscingnibh.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Gale','Bolden','4035 Dellwood Cove','Joanna','29351','USA','US','1971-11-16 00:00:00','19711116-3782',55,'(832)766-3040','penny@nislduis.com',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Shannon','Cline','1694 Nelson Way','Honeoye','14471','USA','US','1970-11-16 00:00:00','19701116-3749',55,'(878)706-2672','j.hairston@ametaccumsan.gov',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Lamar','Mcclendon','8654 Wilchester Drive','White Oak','28399','USA','US','2004-02-17 00:00:00','20040217-3761',55,'(303)244-3798','nereida@nostrudconsequat.eu',$offices[array_rand($offices)]->Id);
        $this->createIfNotExisting('Rodrigo','Stack','3243 Meadow Wood Cove','Mobile','36609','USA','US','1953-02-01 00:00:00','19530201-6011',55,'(508)745-9798','serena@nullapraesent.eu',$offices[array_rand($offices)]->Id);
        

        $seeded = true;

    }

    function createIfNotExisting($givenname, $surname,$Streetaddress, $City, $Zipcode, $Country, $CountryCode, $Birthday, $NationalId, $TelephoneCountryCode, $Telephone, $EmailAddress, $OfficeId){
        $existing = $this->getCustomerByNames($givenname, $surname);
        if($existing){
            return;
        };
        return $this->addCustomer($givenname, $surname,$Streetaddress, $City, $Zipcode, $Country, $CountryCode, $Birthday, $NationalId, $TelephoneCountryCode, $Telephone, $EmailAddress, $OfficeId);

    }

    function addOffice($name,$streetAddress,$city, $Zipcode){
        $prep = $this->pdo->prepare('INSERT INTO Office (Name,StreetAddress, City,Zipcode) VALUES(:name,:streetAddress, :city, :zipcode )');
        $prep->execute(["name"=>$name,"streetAddress"=>$streetAddress,"city"=>$city,"zipcode"=>$Zipcode]);
        return $this->pdo->lastInsertId();
    }


    function updateCustomer($id, $givenname, $surname,$Streetaddress, $City, $Zipcode, $Country, $CountryCode, $Birthday, $NationalId, $TelephoneCountryCode, $Telephone, $EmailAddress, $OfficeId){
        $prep = $this->pdo->prepare("UPDATE  Customer SET
                        GivenName=:GivenName, Surname=:Surname, Streetaddress=:Streetaddress, 
                        City=:City, Zipcode=:Zipcode, Country=:Country, CountryCode=:CountryCode, Birthday=:Birthday, 
                        NationalId=:NationalId,TelephoneCountryCode=:TelephoneCountryCode,Telephone=:Telephone,
                        EmailAddress=:EmailAddress,OfficeId=:OfficeId
                        WHERE id=:id;
        ");
        $prep->execute(["GivenName"=>$givenname,"Surname"=>$surname,"Streetaddress"=>$Streetaddress,"City"=>$City,
            "Zipcode"=>$Zipcode,"Country"=>$Country,
            "CountryCode"=>$CountryCode,"Birthday"=>$Birthday,"NationalId"=>$NationalId,"TelephoneCountryCode"=>$TelephoneCountryCode,
            "Telephone"=>$Telephone,"EmailAddress"=>$EmailAddress,"OfficeId"=>$OfficeId,"id"=>$id]);
                  
    }




    function addCustomer($givenname, $surname,$Streetaddress, $City, $Zipcode, $Country, $CountryCode, $Birthday, $NationalId, $TelephoneCountryCode, $Telephone, $EmailAddress, $OfficeId){
        $prep = $this->pdo->prepare("INSERT INTO Customer
                        (GivenName, Surname, Streetaddress, City, Zipcode, Country, CountryCode, Birthday, NationalId, TelephoneCountryCode, Telephone, EmailAddress, OfficeId)
                    VALUES(:GivenName, :Surname, :Streetaddress, :City, :Zipcode, :Country, :CountryCode, :Birthday, :NationalId, :TelephoneCountryCode, :Telephone, :EmailAddress, :OfficeId);
        ");
        $prep->execute(["GivenName"=>$givenname,"Surname"=>$surname,"Streetaddress"=>$Streetaddress,"City"=>$City,
            "Zipcode"=>$Zipcode,"Country"=>$Country,
            "CountryCode"=>$CountryCode,"Birthday"=>$Birthday,"NationalId"=>$NationalId,"TelephoneCountryCode"=>$TelephoneCountryCode,
            "Telephone"=>$Telephone,"EmailAddress"=>$EmailAddress,"OfficeId"=>$OfficeId]);
        return $this->pdo->lastInsertId();
                   
    }

    function initIfNotInitialized() {

        static $initialized = false;
        if($initialized) return;


        $sql  ="CREATE TABLE IF NOT EXISTS `Office` (
            `Id` INT AUTO_INCREMENT NOT NULL,
            `Name` varchar(200) NOT NULL,
            `StreetAddress` varchar(200) NOT NULL,
            `City` varchar(200) NOT NULL,
            `Zipcode` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
            ) ";

        $this->pdo->exec($sql);
        if(!$this->getOfficeByName("Stiedermann")){
            $this->addOffice("Stiedermann","74454 Vandervort Shore","Mullerbury","42160");
        }
        if(!$this->getOfficeByName("Mckenna Huel DDS")){
            $this->addOffice("Mckenna Huel DDS","888 Kenyon Light","D'Amorehaven","48043");
        }
        if(!$this->getOfficeByName("Cartwright")){
            $this->addOffice("Cartwright","936 Kiehn Route","West Ned","11230");
        }
        if(!$this->getOfficeByName("Mayert")){
            $this->addOffice("Mayert","4059 Carling Avenue","Ottawa","WE-QQQ1-123");
        }
        if(!$this->getOfficeByName("Ritchie")){
            $this->addOffice("Ritchie","96163 Kreiger Cape","Lambertberg","76287-7180");
        }

        $sql  ="CREATE TABLE IF NOT EXISTS `Customer` (
            `Id` int NOT NULL AUTO_INCREMENT,
            `GivenName` varchar(50) NOT NULL,
            `Surname` varchar(50) NOT NULL,
            `Streetaddress` varchar(50) NOT NULL,
            `City` varchar(50) NOT NULL,
            `Zipcode` varchar(10) NOT NULL,
            `Country` varchar(30) NOT NULL,
            `CountryCode` varchar(2) NOT NULL,
            `Birthday` datetime NOT NULL,
            `NationalId` varchar(20) NOT NULL,
            `TelephoneCountryCode` int NOT NULL,
            `Telephone` varchar(20) NOT NULL,
            `EmailAddress` varchar(50) NOT NULL,
            `Popularity` INT,
            `OfficeId` INT NOT NULL,
            PRIMARY KEY (`Id`),
            FOREIGN KEY (`OfficeId`)
                REFERENCES Office(id)
          ) ";

        $this->pdo->exec($sql);

        $this->usersDatabase->setupUsers();
        $this->usersDatabase->seedUsers();

        new Migrator($this->pdo);

        $initialized = true;
    }


}


?>