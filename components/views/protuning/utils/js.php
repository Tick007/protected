<script>
//Открываем попап
function check(){

	
	//alert('ewrwer');
	jQuery.ajax({
		'type':'POST',
		'url':'/site/page?view=order_notify',
		'cache':false,
		'async': true,
		'dataType':'json',
		//'data':form.serialize(),
		//'data':$('#yw0').serialize(),
		'success':function(response){

			//alert('werwer');
			//answer = JSON.parse(response.responseText);
			//num_of_bottles = response.num_of_bottles;
// 			/console.log(num_of_bottles);
			customer = response.customer;
			product = response.product;
			//initial_count = num_of_bottles;
			//if(num_of_bottles>=min_count) {
			//	$('.botnum').html(num_of_bottles);
				$('#cudtomer_name').html(customer);
				$('#popupprod').html(product);
	
				$('.popup_zakaz').toggle();
				setTimeout("closePopup()", 5000);
			//}
			
		},
		'error':function(response, status, xhr){
			//makeAjaxRequest();
			//alert('error');
		}
		});
	

	//
}

/////Закрываем попап
function closePopup(){
	$('.popup_zakaz').toggle();
}

//Returns a random number between min (inclusive) and max (exclusive)
function getRandomArbitrary(min, max) {
  return Math.random() * (max - min) + min;
}

$(document).ready(function(){
	//setInterval(check, 5000);
	//setInterval(check, getRandomArbitrary(1000, 10000));
	setTimeout(check, getRandomArbitrary(50000, 15000))
});
</script>