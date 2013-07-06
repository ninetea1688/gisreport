<!-- Last Updated 26.06.2013 03:33 -->

<?php
$host = "192.168.56.101" ;
$user = "root" ;
$pass = "hospkn" ;
$db = "provisdb" ;
$connect = mysql_connect($host,$user,$pass) or die(mysql_error()) ;
$condb = mysql_select_db($db,$connect) or die(mysql_error()) ;
mysql_query("SET NAMES utf8");   
//mysql_query("SET NAMES TIS620");
?>
<html>    
  <head> 
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="jquery/jquery-1.4.4.min.js"></script>        
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="external/markerwithlabel.js" type="text/javascript"></script>	
    <script type="text/javascript" src="gmap3.js"></script> 
    <link rel="stylesheet" href="samples/style.css" type="text/css">
    <script src="amcharts/amcharts.js" type="text/javascript"></script>    
    <title>ระบบรายงานสถานะการณ์ไข้เลือดออก อำเภอเขื่องใน</title>
    <style>
      .gmap3{
		display:block;
        margin-left: auto;
		margin-right: auto;
        border: 1px dashed #C0C0C0;
        width: 90%;
        height: 90%;
      }
	  .labels {
       color: red;
       background-color: white;
       font-family: "Lucida Grande", "Arial", sans-serif;
       font-size: 11px;
       font-weight: bold;
       text-align: center;
       width: 100px;     
       border: 2px solid black;
       white-space: nowrap;
     }
      #box{
        border:1px solid #FF0000; 
        background-color: #EEEEEE; 
        width:340px;
        height: 72;
        font-size: 14px;
      }
      
      #box .line{
        border-bottom: 1px solid #FFFF00;
        overflow: auto;
        clear: both;
        height: 18px;
      }
      #box #lng-west{
        border-bottom: 0px;
      }
      
      #box .name{
        width: 58px;
        border-right: 1px solid #FF0000;
        float:left;
      }
      #box .value{
        float:left;
      }
      body
{
	line-height: 1.6em;
}
 
 
 
#box-table-a
{
 
	font-size: 14px;
	margin: 45px;
	width: 480px;
	text-align: left;
	border-collapse: collapse;
}
#box-table-a th
{
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;
}
#box-table-a td
{
	padding: 8px;
	background: #e8edff; 
	border-bottom: 1px solid #fff;
	color: #669;
	border-top: 1px solid transparent;
}
#box-table-a tr:hover td
{
	background: #d0dafd;
	color: #339;
}
    </style>
    <script type="text/javascript">
      
    $(function(){


      $('#test1').gmap3(
	  {
	    defaults:{ 
            classes:{
              Marker:MarkerWithLabel
            }
          }, 
        map:{
          options:{
            center:{lat:15.382853,lng:104.554111}, 
            zoom:10, 
            mapTypeId: google.maps.MapTypeId.TERRAIN 
          }
        },
          marker:{
            values:[
			<?php
			  $sqlmaker = mysql_query("select person_gis.idcard,person_gis.fname,person_gis.lname,person_gis.changwat,person_gis.ampor,
person_gis.tambon,person_gis.mooban,person_gis.xgis,person_gis.ygis
from person_gis inner join person_dhf on person_gis.idcard = person_dhf.pop_id
group by person_gis.idcard limit 50") ;
			  while($dbarr = mysql_fetch_array($sqlmaker)){			  
              echo "{latLng:[".$dbarr[ygis].", ".$dbarr[xgis]."], data:'ชื่อ - สกุล :".$dbarr[fname]." ".$dbarr[lname]."' }," ;			  
			  }
			?>
            ], //create info windows
			            options:{
              draggable: false
            },
            events:{
              mouseover: function(marker, event, context){
                var map = $(this).gmap3("get"),
                  infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.open(map, marker);
                  infowindow.setContent(context.data);
                } else {
                  $(this).gmap3({
                    infowindow:{
                      anchor:marker, 
                      options:{content: context.data}
                    }
                  });
                }
              },
              mouseout: function(){
                var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.close();
                }
              }
            }
			},		
        polygon: {
		//Array Collection : I think it would be better if u replace patten likes "echo" for loop function in "Options" patten.But then you will assignments "id" to referencing polygon object krub.
 		//************* begin Array Collection
		values: [
		
					<?php
					$k = 1;
					$sql = "select count(person.tambon) as total,person.tambon,person.ampur,person.changwat,tmb_gis.tmbname,tmb_gis.lat,tmb_gis.lng
from diag dx inner join person on dx.pcucode = person.pcucode and dx.cid = person.cid
inner join tmb_gis on person.tambon = tmb_gis.tambon and person.ampur = tmb_gis.ampur and person.changwat = tmb_gis.changwat
where dx.diagcode IN( 'A90' , 'A910' , 'A911' , 'A919', 'A91' ) and dx.date_serv BETWEEN '20130101' and '20130630'
group by person.tambon,person.ampur,person.changwat order by person.tambon,person.ampur,person.changwat asc" ;                                        
					$query = mysql_query($sql) ;
					while($arr = mysql_fetch_array($query)){
                                            $sqltmb = mysql_query("select count(person.cid) as total,person.tambon,person.ampur,person.changwat 
from person inner join tmb_gis on person.tambon = tmb_gis.tambon and person.ampur = tmb_gis.ampur and person.changwat = tmb_gis.changwat
WHERE person.cid != '9999999999994' and person.tambon = ".$k." group by person.tambon,person.ampur,person.changwat") ;
                                            $arrtmb = mysql_fetch_array($sqltmb) ;
                                            
                                        $totalpmphur = 100000 ;
                                        $totaltambon = $arrtmb[total] ;
                                        $patien = $arr['total'] ;                                      
                                        $tosan = ($patien / $totaltambon) * $totalpmphur ;
                                        //if(($tosan>1)&&($i<25)){echo "เป็นแนวนี้";}
                                        
                                        if($tosan == 0){
					$fcolor = "#00FF00" ; // สีเขียว ปลอดภัย
                                        }
                                        if(($tosan > 1) && ($tosan < 25))
                                        {
                                        $fcolor = "#FFFF00" ; // สีเหลือง พอทน
                                        }
                                        if(($tosan > 25) && ($tosan < 50))
                                        {
                                        $fcolor = "#660000" ; // สีน้ำตาล เฝ้าระวัง
                                        }
                                        if($tosan > 50){
                                        $fcolor = "#FF0000" ; // สีแดง อันตราย
                                        }                                       
					echo "{";
					echo "options:{" ;
					echo "strokeColor: '#FF0000'," ;
					echo "strokeOpacity: 0.8," ;
					echo "strokeWeight: 2," ;
					echo "fillColor: '".$fcolor."'," ;
					echo "fillOpacity: 0.35," ;
					echo "paths:" ;
					echo "[" ;
					$sqlgis = "select * from tmb_gis_poly where tambon = ".$arr[tambon] ;
					$query_gis = mysql_query($sqlgis) ;
					while($arrgis = mysql_fetch_array($query_gis)){
					// ตรงนี้จะ วน loop ข้อมูล gis
							$i = 1;
							$count >= count($arrgis) ;                
							//echo $count;
								if($i<$count){
								echo '['.$arrgis['lat'].','.$arrgis[lng].']' ;
								}else{
								echo '['.$arrgis['lat'].','.$arrgis[lng].'],' ;                  
							}
							$i = $i+1;							
					// สิ้นสุด วน loop ข้อมูล ตรงนี้
					}
					echo "]" ;
					echo "}" ;
					echo "}" ;
					$k = $k + 1;
					if($k=18){
					echo "," ;
					}else{
					echo "" ;					
					}
					}
					?>
//insert another layer heare
// data heare
// end of insert another layer					
				  ],
				  //************* end Array Collection
	  
				}
       
      }
	  
	  //Auto Zoom & Fit Display
	  //,"autofit"
	  );

    });
    </script>
  </head>
    
  <body>
      <div align="center" class="text">ระบบรายงานจำนวนผู้ป่วยไข้เลือดออก แยกตามรายตำบล ภาพรวม อำเภอเขื่องใน</div>
      <br />
    <div id="test1" class="gmap3"></div>
      <br />                         
    
  </body>
</html>