<DOCTYPE html>
<?php

?>
<p id="demo"></p>
<script type="text/JavaScript">

let text = "<table><ul><th>Product foto:</th><th>Product naam:</th><th width= 300px>beschrijving:</th>";

fetch('http://localhost/_php_oop/index.php?action=api&call=webshopitems')
  .then((response) => {
    return response.json()
  })
  .then((data) => {
    // Work with JSON data here
    //console.log(data[0]);
	let fLen = data.length;

   	for (let i = 0; i < fLen; i++) 
   	{
   		text += "<tr><td>" + "<img width=200 height=150 src=http://localhost/_php_oop/assets/shop/"
   		+ data[i].image +"></img></td><td>"+ data[i].productname + "</td><td>" 
   		+ data[i].description + "</td>";
   		console.log(data[i]);
   	}
   	text += "</tr></table>";	
   	console.log(text);

   	document.getElementById("demo").innerHTML = text;
   	
  // If property names are known beforehand, you can also just do e.g.
  // alert(object.id + ',' + object.Title);
  })
  .catch((err) => {
    // Do something for an error here
  })

</script>

</html>

