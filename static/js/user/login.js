
function changeVerification_img(){
	$(".yz_img").attr('src', 'regCheckCode');
}

function register(){
	var name = $(".index_s1").val();
	var mobile = $(".index_s2").val();
	var type = $(".index_s3").val();
	var verifyCode = $(".index_s4").val();
	var reg = /^(13|14|15|17|18)\d{9}$/;
	var match = reg.test(mobile);

	if(name  == ''){
		alert('请填写姓名');
		return false;
	}

	if(match == ''){
		alert('请填写正确的手机号');
		return false;
	}
	
	if(type == ''){
		alert('请选择认证类型');
		return false;
	}
	var url = "ajax_index";
	var data = {'name':name, 'mobile':mobile, 'type':type, 'verifyCode':verifyCode};
	var success = function(backData){
		if(backData.result){
			window.location.href = "product/index";
		}else{
			alert(backData.message);
		}
	}
	$.post(url,data,success,'json');

}
