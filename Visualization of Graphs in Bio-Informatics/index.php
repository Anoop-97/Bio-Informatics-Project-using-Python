<?php
  $uploaddir = './uploads/';
  $uploadfile = $uploaddir . $_FILES['file']['name'];

  if(isset($_POST['submit'])) {
    foreach(glob($uploaddir.'*.*') as $v){
          unlink($v);
    }
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
   
    } 
  }
  $uploadfile = './uploads/'.scandir('./uploads')[2];
?>

<!DOCTYPE html>

<html style="width: 1780px;">
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<p><h1 align="center">Applications of Graphs in Bio Informatics</h1></p>

</head>
<meta charset="utf-8">
<!-- load the d3.js library -->    
<script src="http://d3js.org/d3.v3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<style> /* set the CSS */

body {
  font: 12px sans-serif;
}

.axis path,
.axis line {
/*  fill: none;*/
  stroke: 	#DC143C;
  shape-rendering: crispEdges;
}

.node1,.node2 {
  stroke: #D2691E;
}


.tooltip {
font-weight: bold;	
  position: absolute;
  width: 200px;
  height: 28px;
  pointer-events: none;
}
/*path { 
  stroke: violet;
  fill: none;
}*/
.legend {
		font-size: 16px;         
		font-weight: bold;         
		text-anchor: start;
		}
 
.axis path,
.axis line {
	fill: none;
	stroke: black;
	stroke-width: 1;
	shape-rendering: crispEdges;


li.key {
    border-top-width: 15px;
    border-top-style: solid;
    font-size: .75em;
    width: 10%;
    padding-left: 0;
    padding-right: 0;
}


}
</style>
<body style= "margin-left:10px">
 <center><form method="post" action="./index.php" class="form-inline" enctype="multipart/form-data">
          <label class="file">
            <input type="file" id="file" name="file" class="custom-file-input">
            <span class="custom-file-control"></span>
          </label>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form></center>
	<div class="row">

<div class="col-xs-3" >

	<div class="form-group">
  <label for="comment">Details about the Project:</label>
  <textarea class="form-control" rows="28" id="comment" disabled>
This Visualization shows interaction between various nodes and differentiating them with respective to their edge weights.

Features :
	•	You can upload any provided csv files using choose file option. Nodes and their interactions with respective to the uploaded csv file can be visualized.
	•	Click on any part of the graph to get all the interactions between nodes.
		o	Each edge can be differentiated with different colors based on their edge weight ranges shown below the graph
	•	Click on any node to get the interactions of that particular node with other nodes.
		o	Width of the edge weight is also directly proportional to the edge weight ranges.
		o	Again color of the edge weight is categorized based on the edge weight ranges given below the graph.
	•	Hover on any node to get its coordinated and hover on any edge weight to get the coordinates of the two nodes and the weight between them.
	•	Click on any weight range shown below in order to get all the edge weights that fall in that particular category.


	
  </textarea>
  </div>
</div>
 <div class="col-xs-9">
<script>

$( document ).ready(function() {
// Set the dimensions of the canvas / graph
var margin = {top: 40, right: -350, bottom: 30, left: 80},
    width = 950 - margin.left - margin.right,
    height = 750 - margin.top - margin.bottom;


//setup x
var x = d3.scale.linear()
    .range([40, width]);

// setup y
var y = d3.scale.linear()
    .range([height-30, 0]);



var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");


// add the tooltip area to the webpage
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 100);

// add the graph canvas to the body of the webpage
var svg = d3.select(".row").append("svg")
    .attr("width", width + margin.left + margin.right+400)
    .attr("height", height + margin.top + margin.bottom+50)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	var valueline=d3.svg.line()
						.x(function(d) { 
						//console.log(x(d.x));
						return x(d.x); 
							})
						.y(function(d) { 
						return y(d.y); 
							});

// Get the data
d3.csv("<?php echo $uploadfile;?>", function(error, data) {
	var minwt=99999 ;
	var maxwt=0.0001;
    data.forEach(function(d){
        //console.log(d);
        d.x2 = +d.x2;
        d.y2 = +d.y2;
        d.x1 = +d.x1;
        d.y1 = +d.y1;
        d.wt = +d.wt;

        
    });
	//function for calculating min and max weights
	data.forEach(function(d1){
		if(d1.wt < minwt){
			minwt= d1.wt
		}
	});
	 console.log(minwt)
	 
	data.forEach(function(d2){
		if(d2.wt > maxwt){
			maxwt= d2.wt
		}
	});
	
	var format = d3.format(".0f");
	var start = Number(format((maxwt - minwt)/10))
	
 console.log(maxwt);
    x.domain([0, d3.max(data, function(d) { return d.x2; })]);
    y.domain([0, d3.max(data, function(d) { return d.y2; })]);


    svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .append("text")
      .attr("class", "label")
      .attr("x", width)
      .attr("y", -6)
      .style("text-anchor", "end")
      .text("X-axis");
    
   
    /*data.forEach(function(d){
        //console.log(d);
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
		//console.log(lineData);
        svg.append("path").attr("class", "line").attr("d", valueline(lineData)) ;
	});*/
      

    svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Y-axis");
	  
	  // Add the blue line title
	  
	 svg.append("text")
		.attr("x", 0)             
		.attr("y",  height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",-100)
		.attr("z2",0)
		.style("fill", "Silver")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[0].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[0].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"Silver");
})
		.text(function(d){ return "Negative"});
	  
	svg.append("text")
		.attr("x", 100)             
		.attr("y",  height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",0)
		.attr("z2",start)
		.style("fill", "steelblue")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[1].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[1].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"steelblue");
})
		.text(function(d){ return "0-"+start ;});

// Add the red line title
	svg.append("text")
		.attr("x", 200) 	
		.attr("z1",start)
		.attr("z2",start*2)		
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "pink")         
	.on("click", function(){
		val1=document.getElementsByClassName('legend')[2].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[2].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"pink");
})
		.text(function(d){ return start+"-"+(start*2) ;});
    
		svg.append("text")
		.attr("x", 300) 
		.attr("z1",start*2)
		.attr("z2",start*3)			
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "black")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[3].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[3].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"black");
})
		.text(function(d){ return (start*2)+"-"+(start*3) ;});
		
	svg.append("text")
		.attr("x", 400) 
		.attr("z1",start*3)
		.attr("z2",start*4)	
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "green")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[4].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[4].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"green");
})
			.text(function(d){ return (start*3)+"-"+(start*4) ;});
		
	svg.append("text")
		.attr("x", 500)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*4)
		.attr("z2",start*5)	
		.style("fill", "orange")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[5].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[5].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"orange");
})
		.text(function(d){ return (start*4)+"-"+(start*5) ;});
		
	svg.append("text")
		.attr("x", 600)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*5)
		.attr("z2",start*6)	
		.style("fill", "red")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[6].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[6].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"red");
})
		.text(function(d){ return (start*5)+"-"+(start*6) ;});
		
	svg.append("text")
		.attr("x", 700)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "violet") 
		.attr("z1",start*6)
		.attr("z2",start*7)	        
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[7].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[7].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"violet");
})
		.text(function(d){ return (start*6)+"-"+(start*7) ;});
		
		svg.append("text")
		.attr("x", 800)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*7)
		.attr("z2",start*8)	
		.style("fill", "brown")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[8].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[8].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"brown");
})
		.text(function(d){ return (start*7)+"-"+(start*8) ;});
		
		svg.append("text")
		.attr("x", 900)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*8)
		.attr("z2",start*9)	
		.style("fill", "magenta")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[9].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[9].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"magenta");
})
		.text(function(d){ return (start*8)+"-"+(start*9) ;});
		
		svg.append("text")
		.attr("x", 1000)             
		.attr("y", height + margin.top + 30)
		.attr("z1",start*9)	
		.attr("z2",start*10)	
		.attr("class", "legend")
		.style("fill", "blue")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[10].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[10].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"blue");
})
		.text(function(d){ return (start*9)+"-"+(start*10) ;});
		
		


      svg.selectAll(".node2")     //for x2, y2 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node2")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x2); })
      .attr("cy", function(d) { return y(d.y2); })

    .style("fill", "black")
    .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", .9);
          tooltip.html( "node(" + d["x2"] + "," + d["y2"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      }) 
	 .on("mouseout", function(d) {		
           tooltip.html("")		
        })

	  .on("click", function(d) {
		  
		  function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
		   
		  
		   
           
       }
	  	event.stopPropagation();
	  $(".line").remove();
        d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  <!-- console.log(id); -->
			  var str=(id.wt/start),
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke',dataline(id.wt)).attr("d", valueline(lineData)) 
				.on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
						  // console.log(din);
					  tooltip.html( "Wt : " +(str*start)+"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) 
	    .on("mouseout", function(d) {		
            tooltip.html("")		
        });

			  }
				
			});
});
		
		
		
		
    });


      svg.selectAll(".node1")     //for x1, y1 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node1")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x1); })
      .attr("cy", function(d) { return y(d.y1); })

    .style("fill", "black")
      .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", .9);
          tooltip.html( "Wt : " + d["wt"] + "<br>     node(" + d["x1"] + "," + d["y1"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      })
	    .on("mouseout", function(d) {		
            tooltip.html("")			
        })

	 
	   .on("click", function(d) {
		   
		   function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
		   
		  
		   
           
       }
        //alert("on click" + d.x2+"--"+d.y2);
		event.stopPropagation();
		$(".line").remove();
		 d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  console.log(id.wt);
			  var str=id.wt/start ,node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				
				.attr('stroke',dataline(id.wt))
				.attr("d", valueline(lineData))
				.on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
					  tooltip.html( "Wt : " + (str*start) +"</br>"+node1+"</br>"+node2 )
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) 
	

			  }
				
			});
});
    });
	

	
function ClickReset() {	
console.log(start)
	$(".line").remove();
	data.forEach(function(d){
        //console.log(d);
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
		function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
		   
		  
		   
           
       }

		//console.log(lineData);
        svg.append("path").attr("class", "line")
		.attr('stroke-width', 1)
		.attr('stroke',dataline(d.wt))
.attr("d", valueline(lineData)) ;
	});
}//ClickReset
d3.select("body").on("click", ClickReset);




});
});


 function dpaladhi(thres,thres1,col)
  {
 $("svg").remove();
var margin = {top: 40, right: -350, bottom: 30, left: 80},
    width = 950 - margin.left - margin.right,
    height = 750 - margin.top - margin.bottom;



console.log(col);
//setup x
var x = d3.scale.linear()
    .range([40, width]);

// setup y
var y = d3.scale.linear()
    .range([height-30, 0]);



var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");


// add the tooltip area to the webpage
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 100);

// add the graph canvas to the body of the webpage
var svg = d3.select(".row").append("svg")
    .attr("width", width + margin.left + margin.right+400)
    .attr("height", height + margin.top + margin.bottom+50)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	var valueline=d3.svg.line()
						.x(function(d) { 
						//console.log(x(d.x));
						return x(d.x); 
							})
						.y(function(d) { 
						return y(d.y); 
							});
   var dataset=[];
// Get the data
d3.csv("<?php echo $uploadfile;?>", function(error, data) {
	var minwt=99999 ;
	var maxwt=0.0001;
    data.forEach(function(d){
        //console.log(d);
        d.x2 = +d.x2;
        d.y2 = +d.y2;
        d.x1 = +d.x1;
        d.y1 = +d.y1;
        d.wt = +d.wt;
		if(d.wt > thres && d.wt <thres1){
			dataset.push(d);
			}
        
    });
	//console.log(dataset);
	
	data.forEach(function(d1){
		if(d1.wt < minwt){
			minwt= d1.wt
		}
	});
	 console.log(minwt)
	 
	data.forEach(function(d2){
		if(d2.wt > maxwt){
			maxwt= d2.wt
		}
	});
	
	var format = d3.format(".0f");
	var start = Number(format((maxwt - minwt)/10))
	console.log(typeof(start))
 console.log(maxwt);

    x.domain([0, d3.max(data, function(d) { return d.x2; })]);
    y.domain([0, d3.max(data, function(d) { return d.y2; })]);


    svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .append("text")
      .attr("class", "label")
      .attr("x", width)
      .attr("y", -6)
      .style("text-anchor", "end")
      .text("X-axis");
    
   
    /*data.forEach(function(d){
        //console.log(d);
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
		//console.log(lineData);
        svg.append("path").attr("class", "line").attr("d", valueline(lineData)) ;
	});*/
      

    svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Y-axis");
    
	  // Add the blue line title
// Add the red line title
	  
	 svg.append("text")
		.attr("x", 0)             
		.attr("y",  height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",-100)
		.attr("z2",0)
		.style("fill", "Silver")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[0].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[0].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"Silver");
})
		.text(function(d){ return "Negative"});
	  
	svg.append("text")
		.attr("x", 100)             
		.attr("y",  height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",0)
		.attr("z2",start)
		.style("fill", "steelblue")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[1].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[1].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"steelblue");
})
		.text(function(d){ return "0-"+start ;});

// Add the red line title
	svg.append("text")
		.attr("x", 200) 	
		.attr("z1",start)
		.attr("z2",start*2)		
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "pink")         
	.on("click", function(){
		val1=document.getElementsByClassName('legend')[2].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[2].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"pink");
})
		.text(function(d){ return start+"-"+(start*2) ;});
    
		svg.append("text")
		.attr("x", 300) 
		.attr("z1",start*2)
		.attr("z2",start*3)			
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "black")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[3].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[3].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"black");
})
		.text(function(d){ return (start*2)+"-"+(start*3) ;});
		
	svg.append("text")
		.attr("x", 400) 
		.attr("z1",start*3)
		.attr("z2",start*4)	
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "green")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[4].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[4].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"green");
})
			.text(function(d){ return (start*3)+"-"+(start*4) ;});
		
	svg.append("text")
		.attr("x", 500)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*4)
		.attr("z2",start*5)	
		.style("fill", "orange")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[5].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[5].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"orange");
})
		.text(function(d){ return (start*4)+"-"+(start*5) ;});
		
	svg.append("text")
		.attr("x", 600)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*5)
		.attr("z2",start*6)	
		.style("fill", "red")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[6].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[6].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"red");
})
		.text(function(d){ return (start*5)+"-"+(start*6) ;});
		
	svg.append("text")
		.attr("x", 700)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.style("fill", "violet") 
		.attr("z1",start*6)
		.attr("z2",start*7)	        
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[7].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[7].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"violet");
})
		.text(function(d){ return (start*6)+"-"+(start*7) ;});
		
		svg.append("text")
		.attr("x", 800)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*7)
		.attr("z2",start*8)	
		.style("fill", "brown")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[8].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[8].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"brown");
})
		.text(function(d){ return (start*7)+"-"+(start*8) ;});
		
		svg.append("text")
		.attr("x", 900)             
		.attr("y", height + margin.top + 30)    
		.attr("class", "legend")
		.attr("z1",start*8)
		.attr("z2",start*9)	
		.style("fill", "magenta")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[9].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[9].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"magenta");
})
		.text(function(d){ return (start*8)+"-"+(start*9) ;});
		
		svg.append("text")
		.attr("x", 1000)             
		.attr("y", height + margin.top + 30)
		.attr("z1",start*9)	
		.attr("z2",start*10)	
		.attr("class", "legend")
		.style("fill", "blue")         
		.on("click", function(){
		val1=document.getElementsByClassName('legend')[10].getAttribute('z1')
		val2=document.getElementsByClassName('legend')[10].getAttribute('z2')
		console.log(val1,val2);
		
		dpaladhi(val1,val2,"blue");
})
		.text(function(d){ return (start*9)+"-"+(start*10) ;});
		
		


      svg.selectAll(".node2")     //for x2, y2 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node2")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x2); })
      .attr("cy", function(d) { return y(d.y2); })

    .style("fill", "black")
    .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", .9);
          tooltip.html( "node(" + d["x2"] + "," + d["y2"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      })
	   .on("mouseout", function(d) {	
		tooltip.html("")			
        })
	 .on("click", function(d) {
		 
		 function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
		   
		  
		   
           
       }
	  	event.stopPropagation();
	  $(".line").remove();
        d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  <!-- console.log(id); -->
			  var str=id.wt/start,
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path")
				.attr("class", "line")
				.attr('stroke-width', +str)
				.attr('stroke',dataline(id.wt))
				.attr("d", valueline(lineData)) 
				.on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
						  // console.log(din);
					  tooltip.html( "Wt : " +(str*start) +"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) 
	   .on("mouseout", function(d) {	
		tooltip.html("")			
        });
			  }
				
			});
});
		
		
		
		
    });
	 //d3.csv("<?php echo $uploadfile;?>", function(error, data) {
 dataset.forEach(function(id){
				
				
		//	  if(id.x2==d.x2 && id.y2==d.y2)
			//  {
			  //console.log(id);
			  var str=id.wt/start,
			  node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('style','stroke: red')
				.attr('stroke-width', +str)
				.style('stroke',col).attr("d", valueline(lineData)) 
				.on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9)
	
						  
					  tooltip.html( "Wt : " +(str*start)+"</br>"+node1+"</br>"+node2)
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) 
	   .on("mouseout", function(d) {	
		tooltip.html("")			
        });
		//	  }
				
			});
		//	});
	  
      svg.selectAll(".node1")     //for x1, y1 values
      .data(data)
    .enter().append("circle")
      .attr("class", "node1")
      .attr("r", 3.5)
      .attr("cx", function(d) { return x(d.x1); })
      .attr("cy", function(d) { return y(d.y1); })

    .style("fill", "yellow")
      .on("mouseover", function(d) {
          tooltip.transition()
               .duration(200)
               .style("opacity", .9);
          tooltip.html( "Wt : " + d["wt"] + "<br>     node(" + d["x1"] + "," + d["y1"] + ")")
               .style("left", (d3.event.pageX + 5) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
      })
	   .on("mouseout", function(d) {	
		tooltip.html("")			
        })
	  
	   .on("click", function(d) {
		   function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
		   
		  
		   
           
       }
        //alert("on click" + d.x2+"--"+d.y2);
		event.stopPropagation();
		$(".line").remove();
		 d3.csv("<?php echo $uploadfile;?>", function(error, data) {
				 data.forEach(function(id){
				
				
			  if(id.x2==d.x2 && id.y2==d.y2)
			  {
			  console.log(id.wt);
			  var str=id.wt/start ,node1="node1(" + id.x1+ "," + id.y1 + ")",
			  node2="node2(" + id.x2+ "," + id.y2 + ")";
				lineData=[{x : id.x1,y : id.y1},{x : id.x2,y : id.y2}];
				svg.append("path").attr("class", "line").attr('stroke-width', +str)
				.attr('stroke',dataline(id.wt)).attr("d", valueline(lineData))
				.on("mouseover", function(din) {
					  tooltip.transition()
						   .duration(200)
						   .style("opacity", .9);
					  tooltip.html( "Wt : " + (str*start)+"</br>"+node1+"</br>"+node2 )
						   .style("left", (d3.event.pageX + 5) + "px")
						   .style("top", (d3.event.pageY - 28) + "px");
      }) 
	   .on("mouseout", function(d) {	
		tooltip.html("")			
        });
			  }
				
			});
});
    });
	  
	  function ClickReset() {	
console.log(start)
	$(".line").remove();
	data.forEach(function(d){
        //console.log(d);
		lineData=[{x : d.x1,y : d.y1},{x : d.x2,y : d.y2}];
		function dataline(wt) {
           if(wt<0)
               return 'Silver';
		   else if(wt>0 && wt< start)
			   return 'steelblue';
		   else if(wt>start && wt<start*2)
			   return 'pink';
		   else if(wt>start*2 && wt<start*3)
			   return 'black';
		    else if(wt>start*3 && wt<start*4)
			   return 'green';
		     else if(wt>start*4 && wt<start*5)
			   return 'orange';
		     else if(wt>start*5 && wt<start*6)
			   return 'red';
		     else if(wt>start*6 && wt<start*7)
			   return 'violet';
		     else if(wt>start*7 && wt<start*8)
			   return 'brown';
		     else if(wt>start*8 && wt<start*9)
			   return 'magenta';
			   else(wt>start*9 && wt<start*10)
			   return 'blue';
   
       }

		//console.log(lineData);
        svg.append("path").attr("class", "line")
		.attr('stroke-width', 1)
		.attr('stroke',dataline(d.wt))
.attr("d", valueline(lineData)) ;
	});
}//ClickReset
d3.select("body").on("click", ClickReset);



});
 }


</script>
</div>
</div>
</body>

</html>