function ajaxRequest(url, type, data, dataType, fun){
		$.ajax({
			url : url,
			type : type,
			data : data,
			dataType : dataType,
			success : function(backdata){
				eval(fun);
			},
			error : function(){
			}
		});
}

function in_array(val, array){
	for(var i in array){
		if(val == array[i]){
			return true;
		}
	}
	return false;
}

function trim(str){
  if (str == null) {
    return null;
  }
  return str.replace(/(^\s*)|(\s*$)/g, '');
}
function ltrim(str){
  if (str == null) {
    return null;
  }
  return str.replace(/(^\s*)/g, '');
}
function rtrim(str){
  if (str == null) {
    return '';
  }
  return str.replace(/(\s*$)/g, '');
}

function isBlank(value){
  value = trim(value);
  if (value == null || value == '') {
    return true;
  }
  return false;
}

function isEmpty(value){
  if (value == null || value == '') {
    return true;
  }
  return false;
}

function isPhone(str){
  str = trim(str);
  var reg=/^([0-9]|[\-])+$/;
  if(str.length < 7 || str.length > 18){
    return false;
  }
  return reg.exec(str);
}

function isMobile(str){
  str = trim(str);
  var reg=/^0?(13|15|18|14|17)[0-9]{9}$/;
  return reg.exec(str);
}

/*
*fengyanshan
*/
function isEmail(str){
  str = trim(str);
  var reg = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
  return reg.exec(str);
}

function isImagecode(str){
  str = trim(str);
  if(str.length == 4){
    return false;
  }
  return true;
}

function isSmscode(str){
  str = trim(str);
  if(str.length == 6){
    return false;
  }
  return true;
}

function isPasswordLength(str) {
  if(str.length < 6 || str.length > 20) {
    return false;
  }
  return true;
}

function isPassword(str){
  if(!isPasswordLength(str)) {
    return false;
  }
  var reg=/^.*([\W_a-zA-z0-9-])+.*$/i;
  return reg.exec(str);
}


function timespan(timeout, target, callback){
  var timer = setInterval(function(){
    var day=0, hour=0, minute=0, second = 0;//时间默认值
    if(timeout <= 0){
      clearInterval(timer);
      callback();
    } else {
      day = Math.floor(timeout / (60 * 60 * 24));
      hour = Math.floor(timeout / (60 * 60)) - (day * 24);
      minute = Math.floor(timeout / 60) - (day * 24 * 60) - (hour * 60);
      second = Math.floor(timeout) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
      if (minute <= 9) minute = '0' + minute;
      if (second <= 9) second = '0' + second;
      var html='';
      if(day>0){
        html+=day+'天'+'<s id="h"></s>';
      }
      if(hour>0){
        html+=hour+'时'+'<s></s>';
      }
      if(minute>0){
        html+=minute+'分'+'<s></s>';
      }
      if(second>0){
        html+=second+'秒';
      }
      $(target).html(html);
      timeout--;
    }
  },1000);
}
