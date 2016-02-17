$jKyash = jQuery.noConflict();

$jKyash(document).ready(function(){
	$jKyash('input[id^="p_method_"]').live('click',function(){
        if ($jKyash(this).val() != "kyash") {
            $jKyash("#see_nearby_shops_container").hide();
            $jKyash("#kyash_payment_instructions").hide();
        }
        else {
            KyashJS.parseKyashTags();
            $jKyash("#see_nearby_shops_container").show();
            $jKyash("#kyash_payment_instructions").show();
        }
	});
});
