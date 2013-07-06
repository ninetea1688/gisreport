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
        width: 75%;
        height: 75%;
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
         panel:{
      options:{
        content: '<div id="box">' +
                    '<div id="lat-north" class="line"><div class="name"><font color=#009900>สีเขียว :</font></div><div class="value">อัตราป่วย 0 คน ต่อแสนประชากร</div></div>' +
                    '<div id="lng-east" class="line"><div class="name"><font color=#FFFF00>สีเหลือง :</font></div><div class="value">อัตรป่วยระหว่าง 1 - 24 คน ต่อแสนประชากร</div></div>' +
                    '<div id="lat-south" class="line"><div class="name"><font color=#660000>สีน้ำตาล :</font></div><div class="value">อัตราผู้ป่วย 25 - 50 คน ต่อแสนประชากร</div></div>' +
                    '<div id="lng-west" class="line"><div class="name"><font color=#FF0000>สีแดง :</font></div><div class="value">อัตราป่วยมากกว่า 50 คน ต่อแสน ประชากร</div></div>' +
                  '</div>',
        middle: true,
        right: true
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
			  $kk = 0;
			  $sqlmaker = mysql_query("select count(person.tambon) as total,person.tambon,person.ampur,person.changwat,tmb_gis.tmbname,tmb_gis.lat,tmb_gis.lng
from diag dx inner join person on dx.pcucode = person.pcucode and dx.cid = person.cid
inner join tmb_gis on person.tambon = tmb_gis.tambon and person.ampur = tmb_gis.ampur and person.changwat = tmb_gis.changwat
where dx.diagcode IN( 'A90' , 'A910' , 'A911' , 'A919', 'A91' ) and dx.date_serv BETWEEN '20130101' and '20130630'
group by person.tambon,person.ampur,person.changwat order by person.tambon,person.ampur,person.changwat asc") ;
			  while($dbarr = mysql_fetch_array($sqlmaker)){			  
              echo "{latLng:[".$dbarr[lat].", ".$dbarr[lng]."], data:'ข้อมูลตำบล :".$dbarr[tmbname]." ยอดผู้ป่วยไข้เลือดออกจำนวน : ".$dbarr[total]." คน'," ;
			  echo "options:{" ;
              echo "labelContent: '$dbarr[tmbname]'," ;
              echo "labelAnchor: new google.maps.Point(52, -2)," ;
              echo "labelClass: 'labels'," ;
              echo "labelStyle: {opacity: 0.75}," ;
              echo "labelContent: 'ตำบล:$dbarr[tmbname]'" ;
             echo "}}" ;
			  if($kk != 18){
			  echo "," ;
			  }else{
			  echo "";
			  }
			  $kk = $kk+ 1 ;
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
				  ]
				  //************* end Array Collection
				  
				  
				  ,onces:{
					click: function(polygon, event){
					  var vertices = polygon.getPath(),
						contentString = 'จำนวนผู้ป่วย ไข้เลือดออก ทั้งหมด';
					  					  
			  
					  $(this).gmap3({
						infowindow:{
						  options:{
							content: contentString,
							position:event.latLng
						  }
						}
					  });
					}
				  }
				  
				}
       
      }
	  
	  //Auto Zoom & Fit Display
	  ,"autofit"
	  );

    });
    </script>
        <script type="text/javascript">
            var chart;

            var chartData = [
             <?php
             $g = 1 ;
             $d = 0;
             $gcolor = array('#FF6600', '#FCD202', '#B0DE09', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000','#754DEB','#DDDDDD','#754DEB','#CD0D74','#8A0CCF','#F8FF01') ;
                $sqlg = mysql_query("select count(person.tambon) as total,person.tambon,person.ampur,person.changwat,tmb_gis.tmbname,tmb_gis.lat,tmb_gis.lng
from diag dx inner join person on dx.pcucode = person.pcucode and dx.cid = person.cid
inner join tmb_gis on person.tambon = tmb_gis.tambon and person.ampur = tmb_gis.ampur and person.changwat = tmb_gis.changwat
where dx.diagcode IN( 'A90' , 'A910' , 'A911' , 'A919', 'A91' ) and dx.date_serv BETWEEN '20130101' and '20130630'
group by person.tambon,person.ampur,person.changwat") ;
                while($arrg = mysql_fetch_array($sqlg)){
                 $countg = count($arrg) ;
                echo "{" ;
                echo "tambon: '".$arrg[tmbname]."'," ;
                echo "visits: ".$arrg[total]."," ;                
                echo "color: '".$gcolor[$d]."'" ;     
                echo "}" ;
                $d =$d+ 1 ;
                $g = $g++  ;
                                if($g==$countg){
                    echo "";
                }else{
                    echo ",";
                }
                }
            ?>
        ];


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "tambon";                
                // the following two lines makes chart 3D
                chart.depth3D = 20;
                chart.angle = 30;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90;
                categoryAxis.dashLength = 5;
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.title = "จำนวน ต่อแสนประชากร";
                valueAxis.dashLength = 5;
                chart.addValueAxis(valueAxis);

                // GRAPH            
                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";                
                graph.balloonText = "[[category]]: [[value]]";
                graph.colorField = "color";
                graph.type = "column";                
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                chart.addGraph(graph);

                // WRITE
                chart.write("chartdiv");
            });
        </script>    
  </head>
    
  <body>
      <div align="center" class="text">ระบบรายงานจำนวนผู้ป่วยไข้เลือดออก แยกตามรายตำบล ภาพรวม อำเภอเขื่องใน</div>
      <br />
    <div id="test1" class="gmap3"></div>
      <br />
      <div  align="center" id="chartdiv" style="width: 60%; height: 400px;"></div>
      <br />
      <div align="center">
          <table id="box-table-a">
  <thead>
    	<tr>
            <th scope="col">ตำบล</th>
            <th scope="col">ยอดคนไข้</th>            
        </tr>
    </thead>
    <tbody>
        <?php
$sqlmaker = mysql_query("select count(person.tambon) as total,person.tambon,person.ampur,person.changwat,tmb_gis.tmbname,tmb_gis.lat,tmb_gis.lng
from diag dx inner join person on dx.pcucode = person.pcucode and dx.cid = person.cid
inner join tmb_gis on person.tambon = tmb_gis.tambon and person.ampur = tmb_gis.ampur and person.changwat = tmb_gis.changwat
where dx.diagcode IN( 'A90' , 'A910' , 'A911' , 'A919', 'A91' ) and dx.date_serv BETWEEN '20130101' and '20130630'
group by person.tambon,person.ampur,person.changwat order by total desc") ;
        while($dbarr = mysql_fetch_array($sqlmaker)){
    	echo "<tr>" ;
            echo "<td>ตำบล :".$dbarr[tmbname]." </td>" ;
            echo "<td>".$dbarr[total]."</td>" ;
        echo "</tr>" ;
        }
        ?>
    </tbody>
</table>
      </div>
      <br />
              
    
  </body>
</html>