$jKyash = jQuery.noConflict();

//var pincodePlaceHolder = 'Enter Pincode';

$jKyash(document).ready(function(){
	$jKyash('input[id^="p_method_"]').live('click',function(){
        if ($jKyash(this).val() != "kyash") {
            $jKyash("#see_nearby_shops_container").hide();
            $jKyash("#kyash_payment_instructions").hide();
        }
        else {
            $jKyash("#see_nearby_shops_container").show();
            $jKyash("#kyash_payment_instructions").show();
        }
	});

    /*
	$jKyash('#kyash_postcode').live('focus',function(){
		if($jKyash(this).val() == pincodePlaceHolder)
		{
			$jKyash(this).val('');
		}
	});
	
	$jKyash('#kyash_postcode').live('blur',function(){
		if($jKyash(this).val().length == 0)
		{
			$jKyash(this).val(pincodePlaceHolder);
		}
	});
	*/
	
});
/*
function openShops(url,loader)
{
	//$jKyash('#kyash_postcode_payment').parent().removeAttr('for');
	//$jKyash('#kyash_postcode').removeAttr('disabled');
	//$jKyash('#kyash_postcode_button').removeAttr('disabled');
	//$jKyash('#kyash_postcode_payment_sub').show();
	$jKyash('#see_nearby_shops_container').hide();
	document.getElementById('p_method_kyash').checked = true;
	$jKyash('#kyash_open').hide();
	pullNearByShops(url,loader);
}

var old_postcode = '';
var errorMessage = 'Due to some unexpected errors, this is not available at the moment. We are working on fixing it.';

function closeShops()
{
	$jKyash('#see_nearby_shops_container').hide();
	$jKyash('#kyash_close').hide();
}

function pullNearByShops(url,loader)
{
	closeShops();
	postcode = $jKyash('#kyash_postcode').val();
	if(postcode.length == 0 || postcode == pincodePlaceHolder)
	{
		alert('Enter your post code to retrieve the shops');
	}
	else
	{
		if(old_postcode == postcode)
		{
			$jKyash('#see_nearby_shops_container').show();
			$jKyash('#kyash_close').show();
		}
		else
		{
			$jKyash('#see_nearby_shops_container').show();
			var loader = '<img src="'+loader+'" title="Processing..." alt="Processing...">';
			$jKyash('#see_nearby_shops_container').html(loader);
			$jKyash.ajax({
				 url: url+'?postcode='+postcode, 
				 success: function(output, textStatus, xhr){
					 if(xhr.status == 400 || xhr.status == 200)
					 {
						$jKyash('#see_nearby_shops_container').html(output);
					 }
					 else
					 {
						 $jKyash('#see_nearby_shops_container').html(errorMessage);
					 }
					 old_postcode = postcode;
					 $jKyash('#kyash_close').show();
				 }
			});
		}
	}
}
*/